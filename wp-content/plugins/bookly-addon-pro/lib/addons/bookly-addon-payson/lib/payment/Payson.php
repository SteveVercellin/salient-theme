<?php
namespace BooklyPayson\Lib\Payment;

use Bookly\Lib as BooklyLib;
use BooklyPayson\Lib\Payment\Payson\Api;

/**
 * Class Payson
 * @package Bookly\Lib\Payment
 */
class Payson
{
    // Array for cleaning Payson request
    public static $remove_parameters = array( 'bookly_action', 'bookly_fid', 'error_msg' );

    const STATUS_SUCCESS    = 'success';
    const STATUS_DENIED     = 'denied';
    const STATUS_INVALID    = 'invalid';
    const STATUS_PROCESSING = 'processing';

    public static function renderForm( $form_id, $page_url )
    {
        $userData = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            $replacement = array(
                '%form_id%'      => $form_id,
                '%response_url%' => esc_attr( $page_url ),
                '%gateway%'      => BooklyLib\Entities\Payment::TYPE_PAYSON,
                '%back%'         => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ),
                '%next%'         => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_step_payment_button_next' ),
                '%align_class%'  => get_option( 'bookly_app_align_buttons_left' ) ? 'bookly-left' : 'bookly-right',
            );
            $form = '<form method="post" class="bookly-%gateway%-form">
                <input type="hidden" name="bookly_fid" value="%form_id%"/>
                <input type="hidden" name="bookly_action" value="payson-checkout"/>
                <input type="hidden" name="response_url" value="%response_url%"/>
                <button class="bookly-back-step bookly-js-back-step bookly-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40"><span class="ladda-label">%back%</span></button>
                <div class="%align_class%">
                    <button class="bookly-next-step bookly-js-next-step bookly-btn ladda-button" data-style="zoom-in" data-spinner-size="40"><span class="ladda-label">%next%</span></button>
                </div>
             </form>';
            echo strtr( $form, $replacement );
        }
    }

    /**
     * Notification for customer
     *
     * @param BooklyLib\UserBookingData $userData
     * @param string $status    success || error || processing
     * @param string $message
     */
    private static function _redirectTo( BooklyLib\UserBookingData $userData, $status = 'success', $message = '' )
    {
        $userData->load();
        $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_PAYSON, $status, $message );
        $userData->sessionSave();

        @wp_redirect( remove_query_arg( self::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
        exit;
    }

    /**
     * Redirect to Payson Checkout page, or step payment.
     *
     * @param $form_id
     * @param BooklyLib\UserBookingData $userData
     * @param string $page_url
     */
    public static function paymentPage( $form_id, BooklyLib\UserBookingData $userData, $page_url )
    {
        // Create payment.
        $cart_info =  $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_PAYSON );
        $payment = new BooklyLib\Entities\Payment();
        $payment->setType( BooklyLib\Entities\Payment::TYPE_PAYSON )
            ->setStatus( BooklyLib\Entities\Payment::STATUS_PENDING )
            ->setCartInfo( $cart_info )
            ->save();
        $order = $userData->save( $payment );

        // Checkout.
        $api = self::_getApi();

        $checkout_uri     = add_query_arg( array( 'bookly_action' => 'payson-confirm', 'bookly_fid' => $form_id ), $page_url );
        $confirmation_uri = add_query_arg( array( 'bookly_action' => 'payson-confirm', 'bookly_fid' => $form_id ), $page_url );
        $notification_uri = add_query_arg( array( 'bookly_action' => 'payson-ipn' ), $page_url );
        $terms_uri        = add_query_arg( array( 'bookly_action' => 'payson-terms' ), $page_url );

        $merchant = new Payson\Merchant( $checkout_uri, $confirmation_uri, $notification_uri, $terms_uri, 1 );
        $pay_data = new Payson\PayData( get_option( 'bookly_pmt_currency' ) );

        $item = new Payson\OrderItem( $userData->cart->getItemsTitle( 200 ), $cart_info->getGatewayAmount(), 1, 0, $payment->getId() );
        $pay_data->addOrderItem( $item );

        $first_name = $userData->getFirstName();
        $last_name  = $userData->getLastName();
        // Check if defined First name
        if ( ! $first_name ) {
            $full_name  = $userData->getFullName();
            $first_name = strtok( $full_name, ' ' );
            $last_name  = strtok( '' );
        }

        $customer = new Payson\Customer( $first_name, $last_name, $userData->getEmail(), $userData->getPhone() );
        $checkout = new Payson\Checkout( $merchant, $pay_data, $customer );

        try {
            $checkout_id = $api->createCheckout( $checkout );
            $checkout    = $api->getCheckout( $checkout_id );
            if ( $checkout->status == 'created' ) {
                BooklyLib\Session::setFormVar( $form_id, 'payson.checkout_id', $checkout_id );
                $coupon = $userData->getCoupon();

                if ( $coupon ) {
                    $coupon->claim();
                    $coupon->save();
                }
                $payment->setDetailsFromOrder( $order, $cart_info )->save();
                $userData->sessionSave();

                header( 'Location: ' . $api->getEmbeddedCheckoutUrl( $checkout_id ) );
                exit;
            } else {
                self::_deleteAppointments( $order );
                $payment->delete();
                self::_redirectTo( $userData, 'error', __( 'Incorrect payment data', 'bookly' ) );
            }
        } catch ( \Exception $e ) {
            self::_deleteAppointments( $order );
            $payment->delete();
            self::_redirectTo( $userData, 'error', $e->getMessage() );
        }
    }

    /**
     * Handles IPN messages
     */
    public static function ipn()
    {
        self::confirm( $_GET['checkout'] );

        wp_send_json_success();
    }

    /**
     * @param string $checkout_id
     * @return string
     */
    public static function confirm( $checkout_id )
    {
        $api      = self::_getApi();
        $checkout = $api->getCheckout( $checkout_id );

        // https://tech.payson.se/checkout-statuses/
        switch ( $checkout->status ) {
            case 'readyToShip': // Order is ready to ship.
                $payment_id = $checkout->pay_data->items[0]->reference;
                /** @var BooklyLib\Entities\Payment $payment */
                $payment = BooklyLib\Entities\Payment::query()->where( 'type', BooklyLib\Entities\Payment::TYPE_PAYSON )
                    ->where( 'id', $payment_id )
                    ->findOne();
                if ( $payment ) {
                    if ( $payment->getStatus() == BooklyLib\Entities\Payment::STATUS_COMPLETED ) {
                        return self::STATUS_SUCCESS;
                    } else {
                        $paid     = (float) $checkout->pay_data->items[0]->unit_price;
                        $expected = (float) $payment->getPaid();
                        if ( abs ( $expected - $paid ) > 0.01
                            || $checkout->pay_data->currency != strtolower( get_option( 'bookly_pmt_currency' ) ) )
                        {
                            return self::STATUS_INVALID;
                        }
                        $payment
                            ->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )
                            ->save();
                        foreach (
                            BooklyLib\Entities\Appointment::query( 'a' )
                                ->leftJoin( 'CustomerAppointment', 'ca', 'a.id = ca.appointment_id' )
                                ->where( 'ca.payment_id', $payment_id )->find() as $appointment
                        ) {
                            BooklyLib\Proxy\Pro::syncGoogleCalendarEvent( $appointment );
                            BooklyLib\Proxy\OutlookCalendar::syncEvent( $appointment );
                        }
                        if ( $order = BooklyLib\DataHolders\Booking\Order::createFromPayment( $payment ) ) {
                            BooklyLib\Notifications\Cart\Sender::send( $order );
                        }
                        return self::STATUS_SUCCESS;
                    }
                }
                break;
            case 'canceled':    // Either the merchant or the customer has cancelled the order.
            case 'expired':     // Will be set after a checkout has expired.
            case 'denied':      // Payson will set the status to denied if the purchase is denied by any reason
                return self::STATUS_DENIED;
        }

        return self::STATUS_PROCESSING;
    }

    /**
     * @return Api
     */
    private static function _getApi()
    {
        $agent       = get_option( 'bookly_payson_api_agent_id' );
        $api_key     = get_option( 'bookly_payson_api_key' );

        return new Api( $agent, $api_key, ( get_option( 'bookly_payson_sandbox' ) == 1 ) );
    }

    /**
     * @param BooklyLib\DataHolders\Booking\Order $order
     */
    private static function _deleteAppointments( BooklyLib\DataHolders\Booking\Order $order )
    {
        foreach ( $order->getFlatItems() as $item ) {
            $item->getCA()->deleteCascade( true );
        }
    }
}