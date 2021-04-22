<?php
namespace BooklyMollie\Lib\Payment;

use Bookly\Lib as BooklyLib;
use BooklyMollie\Lib;

/**
 * Class Mollie
 */
class Mollie
{
    // Array for cleaning Mollie request
    public static $remove_parameters = array( 'bookly_action', 'bookly_fid', 'error_msg' );

    public static function renderForm( $form_id, $page_url )
    {
        $userData = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            $replacement = array(
                '%form_id%'      => $form_id,
                '%gateway%'      => BooklyLib\Entities\Payment::TYPE_MOLLIE,
                '%response_url%' => esc_attr( $page_url ),
                '%back%'         => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ),
                '%next%'         => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_step_payment_button_next' ),
                '%align_class%'  => get_option( 'bookly_app_align_buttons_left' ) ? 'bookly-left' : 'bookly-right',
            );
            $form = '<form method="post" class="bookly-%gateway%-form">
                <input type="hidden" name="bookly_fid" value="%form_id%"/>
                <input type="hidden" name="bookly_action" value="mollie-checkout"/>
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
     * Handles IPN messages
     */
    public static function ipn()
    {
        $payment_details = self::_getApi()->payments->get( $_REQUEST['id'] );
        Mollie::handlePayment( $payment_details );
    }

    /**
     * Check gateway data and if ok save payment info
     *
     * @param \Mollie_API_Object_Payment $details
     */
    public static function handlePayment( \Mollie_API_Object_Payment $details )
    {
        /** @var BooklyLib\Entities\Payment $payment */
        $payment = BooklyLib\Entities\Payment::query()->where( 'type', BooklyLib\Entities\Payment::TYPE_MOLLIE )
            ->where( 'id', $details->metadata->payment_id )->findOne();
        if ( $details->isPaid() ) {
            // Handle completed card & bank transfers here
            $total    = (float) $payment->getPaid();
            $received = (float) $details->amount;

            if ( $payment->getStatus() == BooklyLib\Entities\Payment::STATUS_COMPLETED
                 || abs ( $received - $total ) > 0.01
            ) {
                wp_send_json_success();
            } else {
                $payment->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )->save();
                if ( $order = BooklyLib\DataHolders\Booking\Order::createFromPayment( $payment ) ) {
                    BooklyLib\Notifications\Cart\Sender::send( $order );
                }
                foreach (
                    BooklyLib\Entities\Appointment::query( 'a' )
                        ->leftJoin( 'CustomerAppointment', 'ca', 'a.id = ca.appointment_id' )
                        ->where( 'ca.payment_id', $details->metadata->payment_id )->find() as $appointment
                ) {
                    BooklyLib\Proxy\Pro::syncGoogleCalendarEvent( $appointment );
                    BooklyLib\Proxy\OutlookCalendar::syncEvent( $appointment );
                }
            }
        } elseif ( ! $details->isOpen() && ! $details->isPending() ) {
            /** @var BooklyLib\Entities\CustomerAppointment $ca */
            foreach ( BooklyLib\Entities\CustomerAppointment::query()->where( 'payment_id', $details->metadata->payment_id )->find() as $ca ) {
                $ca->deleteCascade();
            }
            $payment->delete();
        }
        wp_send_json_success();
    }

    /**
     * Redirect to Mollie Payment page, or step payment.
     *
     * @param $form_id
     * @param BooklyLib\UserBookingData $userData
     * @param string $page_url
     */
    public static function paymentPage( $form_id, BooklyLib\UserBookingData $userData, $page_url )
    {
        if ( get_option( 'bookly_pmt_currency' ) != 'EUR' ) {
            $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_MOLLIE, 'error', __( 'Mollie accepts payments in Euro only.', 'bookly' ) );
            $userData->sessionSave();

            @wp_redirect( remove_query_arg( self::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
            exit;
        }

        $cart_info = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_MOLLIE );

        $coupon  = $userData->getCoupon();
        $payment = new BooklyLib\Entities\Payment();
        $payment->setType( BooklyLib\Entities\Payment::TYPE_MOLLIE )
            ->setStatus( BooklyLib\Entities\Payment::STATUS_PENDING )
            ->setCartInfo( $cart_info )
            ->save();
        $order = $userData->save( $payment );
        try {
            $api = self::_getApi();
            $mollie_payment = $api->payments->create( array(
                'amount'      => $cart_info->getGatewayAmount(),
                'description' => $userData->cart->getItemsTitle( 125 ),
                'redirectUrl' => add_query_arg( array( 'bookly_action' => 'mollie-response', 'bookly_fid' => $form_id ), $page_url ),
                'webhookUrl'  => add_query_arg( array( 'bookly_action' => 'mollie-ipn' ), $page_url ),
                'metadata'    => array( 'payment_id' => $payment->getId() ),
                'issuer'      => null
            ) );
            if ( $mollie_payment->isOpen() ) {
                if ( $coupon ) {
                    $coupon->claim();
                    $coupon->save();
                }
                $payment->setDetailsFromOrder( $order, $cart_info )->save();
                $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_MOLLIE, 'pending', $mollie_payment->id );
                $userData->sessionSave();

                header( 'Location: ' . $mollie_payment->getPaymentUrl() );
                exit;
            } else {
                self::_deleteAppointments( $order );
                $payment->delete();
                self::_redirectTo( $userData, 'error', __( 'Mollie error.', 'bookly' ) );
            }
        } catch ( \Exception $e ) {
            self::_deleteAppointments( $order );
            $payment->delete();
            $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_MOLLIE, 'error', $e->getMessage() );
            $userData->sessionSave();

            @wp_redirect( remove_query_arg( self::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
            exit;
        }
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

    private static function _getApi()
    {
        include_once Lib\Plugin::getDirectory() . '/lib/payment/Mollie/API/Autoloader.php';
        $mollie = new \Mollie_API_Client();
        $mollie->setApiKey( get_option( 'bookly_mollie_api_key' ) );

        return $mollie;
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
        $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_MOLLIE, $status, $message );
        $userData->sessionSave();

        @wp_redirect( remove_query_arg( self::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
        exit;
    }

    /**
     * Get Mollie Payment
     *
     * @param string $tr_id
     * @return \Mollie_API_Object_Payment
     */
    public static function getPayment( $tr_id )
    {
        $api = self::_getApi();

        return $api->payments->get( $tr_id );
    }

}