<?php
namespace BooklyPayuLatam\Lib\Payment;

use Bookly\Lib as BooklyLib;
use BooklyPayuLatam\Lib\ProxyProviders\Shared;

/**
 * Class PayuLatam
 * @package BooklyPayuLatam\Lib\Payment
 */
class PayuLatam
{
    // Array for cleaning PayU Latam request
    public static $remove_parameters = array( 'bookly_action', 'bookly_fid', 'error_msg', 'merchantId', 'merchant_name', 'merchant_address', 'telephone', 'merchant_url', 'transactionState', 'lapTransactionState', 'message', 'referenceCode', 'reference_pol', 'transactionId', 'description', 'trazabilityCode', 'cus', 'orderLanguage', 'extra1', 'extra2', 'extra3', 'polTransactionState', 'signature', 'polResponseCode', 'lapResponseCode', 'risk', 'polPaymentMethod', 'lapPaymentMethod', 'polPaymentMethodType', 'lapPaymentMethodType', 'installmentsNumber', 'TX_VALUE', 'TX_TAX', 'currency', 'lng', 'pseCycle', 'buyerEmail', 'buyerFullName', 'pseBank', 'pseReference1', 'pseReference2', 'pseReference3', 'authorizationCode', 'processingDate', );
    // developers.payulatam.com/en/web_checkout/sandbox.html
    CONST SANDBOX_API_KEY = '4Vj8eK4rloUd272L48hsrarnUA';
    CONST SANDBOX_API_MERCHANT_ID = '508029';
    CONST SANDBOX_API_ACCOUNT_ID  = '512322';
    CONST APPROVED = 4;

    /**
     * Make array for fill PayU Latam form.
     *
     * @param string $form_id
     * @return array
     */
    public static function replaceData( $form_id )
    {
        $replacement = array();
        $userData    = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            if ( get_option( 'bookly_payu_latam_sandbox' ) == 1 ) {
                $api_key     = PayuLatam::SANDBOX_API_KEY;
                $merchant_id = PayuLatam::SANDBOX_API_MERCHANT_ID;
                $account_id  = PayuLatam::SANDBOX_API_ACCOUNT_ID;
                $action      = 'https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/';
                $test        = 1;
            } else {
                $api_key     = get_option( 'bookly_payu_latam_api_key' );
                $merchant_id = get_option( 'bookly_payu_latam_api_merchant_id' );
                $account_id  = get_option( 'bookly_payu_latam_api_account_id' );
                $action      = 'https://checkout.payulatam.com/ppp-web-gateway-payu/';
                $test        = '0';
            }
            $name = $userData->getFullName();
            if ( ! $name ) {
                $name = trim( rtrim( $userData->getFirstName() ) . ' ' . ltrim( $userData->getLastName() ) );
            }
            $reference_code = wp_generate_password( 16, false );
            $cart_info = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_PAYULATAM );
            $cart_info->setGatewayTaxCalculationRule( 'tax_in_the_price' );
            $replacement = array(
                '%accountId%'     => $account_id,
                '%action%'        => $action,
                '%amount%'        => $cart_info->getGatewayAmount(),
                '%buyerEmail%'    => esc_attr( $userData->getEmail() ),
                '%buyerFullName%' => esc_attr( substr( $name, 0, 150 ) ),
                '%currency%'      => get_option( 'bookly_pmt_currency' ),
                '%description%'   => esc_attr( $userData->cart->getItemsTitle( 255 ) ),
                '%gateway%'       => BooklyLib\Entities\Payment::TYPE_PAYULATAM,
                '%merchantId%'    => $merchant_id,
                '%referenceCode%' => $reference_code,
                '%signature%'     => md5( implode( '~', array( $api_key, $merchant_id, $reference_code, $cart_info->getGatewayAmount(), get_option( 'bookly_pmt_currency' ) ) ) ),
                '%tax%'           => $cart_info->getGatewayTax(),
                '%test%'          => $test,
                '%back%'          => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ),
                '%next%'          => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_step_payment_button_next' ),
                '%align_class%'   => get_option( 'bookly_app_align_buttons_left' ) ? 'bookly-left' : 'bookly-right',
            );
        }

        return $replacement;
    }

    /**
     * Render PayU Latam form.
     *
     * @param string $form_id
     * @param string $page_url
     */
    public static function renderForm( $form_id, $page_url )
    {
        $replacement                      = self::replaceData( $form_id );
        $replacement['%responseUrl%']     = esc_attr( add_query_arg( array( 'bookly_action' => 'payu_latam-checkout', 'bookly_fid' => $form_id ), $page_url ) );
        $replacement['%confirmationUrl%'] = esc_attr( add_query_arg( array( 'bookly_action' => 'payu_latam-ipn' ), $page_url ) );

        if ( ! empty( $replacement ) ) {
            $form = '<form action="%action%" method="post" class="bookly-%gateway%-form" data-gateway="%gateway%">
                <input type="hidden" name="accountId" value="%accountId%">
                <input type="hidden" name="amount" value="%amount%">
                <input type="hidden" name="buyerEmail" value="%buyerEmail%">
                <input type="hidden" name="buyerFullName" value="%buyerFullName%">
                <input type="hidden" name="confirmationUrl" value="%confirmationUrl%">
                <input type="hidden" name="currency" value="%currency%">
                <input type="hidden" name="description" value="%description%">
                <input type="hidden" name="discount" value="0">
                <input type="hidden" name="extra1" value="" class="bookly-payment-id">
                <input type="hidden" name="merchantId" value="%merchantId%">
                <input type="hidden" name="referenceCode" value="%referenceCode%">
                <input type="hidden" name="responseUrl" value="%responseUrl%">
                <input type="hidden" name="shipmentValue" value="0.00">
                <input type="hidden" name="signature" value="%signature%">
                <input type="hidden" name="tax" value="%tax%">
                <input type="hidden" name="taxReturnBase" value="0">
                <input type="hidden" name="test" value="%test%">
                <button class="bookly-back-step bookly-js-back-step bookly-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40"><span class="ladda-label">%back%</span></button>
                <div class="%align_class%">
                    <button class="bookly-next-step bookly-js-next-step bookly-btn ladda-button" data-style="zoom-in" data-spinner-size="40"><span class="ladda-label">%next%</span></button>
                </div>
            </form>';

            echo strtr( $form, $replacement );
        }
    }

    /**
     * Payment is Approved when signature correct and amount equal appointment price
     *
     * @param int    $transaction_status
     * @param string $reference_code
     * @param string $signature
     * @return bool
     */
    public static function processPayment( $transaction_status, $reference_code, $signature )
    {
        $payment_id = (int) $_REQUEST['extra1'];
        $payment = new BooklyLib\Entities\Payment();
        $payment->loadBy( array( 'id' => $payment_id, 'type' => BooklyLib\Entities\Payment::TYPE_PAYULATAM ) );
        $paid    = (float) $payment->getPaid();
        if ( $_REQUEST['bookly_action'] === 'payu_latam-ipn' ) {
            // PayU Latam IPN
            $received = (float) $_REQUEST['value'];
        } else {
            // PayU Latam Checkout
            $received = (float) $_REQUEST['TX_VALUE'];
        }

        if ( $paid != $received ) {
            // Difference in the expected and received payment.
            return false;
        }
        $processed = false;
        if ( get_option( 'bookly_payu_latam_sandbox' ) == 1 ) {
            $api_key     = PayuLatam::SANDBOX_API_KEY;
            $merchant_id = PayuLatam::SANDBOX_API_MERCHANT_ID;
        } else {
            $api_key     = get_option( 'bookly_payu_latam_api_key' );
            $merchant_id = get_option( 'bookly_payu_latam_api_merchant_id' );
        }
        $TX_VALUE = number_format( round( $received, 1, PHP_ROUND_HALF_EVEN ), 1, '.', '' );
        if ( $signature == md5( implode( '~', array( $api_key, $merchant_id, $reference_code, $TX_VALUE, get_option( 'bookly_pmt_currency' ), $transaction_status ) ) ) ) {
            if ( $payment->getStatus() == BooklyLib\Entities\Payment::STATUS_COMPLETED ) {
                $processed = true;
            } else {
                switch ( $transaction_status ) {
                    case PayuLatam::APPROVED:
                        $processed = true;
                        $payment->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )->save();
                        if ( $order = BooklyLib\DataHolders\Booking\Order::createFromPayment( $payment ) ) {
                            BooklyLib\Notifications\Cart\Sender::send( $order );
                        }
                        foreach (
                            BooklyLib\Entities\Appointment::query( 'a' )
                                ->leftJoin( 'CustomerAppointment', 'ca', 'a.id = ca.appointment_id' )
                                ->where( 'ca.payment_id', $payment_id )->find() as $appointment
                        ) {
                            BooklyLib\Proxy\Pro::syncGoogleCalendarEvent( $appointment );
                            BooklyLib\Proxy\OutlookCalendar::syncEvent( $appointment );
                        }
                        break;
                    case 6:     // Transaction rejected
                        /** @var BooklyLib\Entities\CustomerAppointment $ca */
                        foreach ( BooklyLib\Entities\CustomerAppointment::query()->where( 'payment_id', $payment->getId() )->find() as $ca ) {
                            $ca->deleteCascade();
                        }
                        $payment->delete();
                        break;
                }
            }
        }

        return $processed;
    }

    /**
     * Handles IPN messages
     */
    public static function ipn()
    {
        self::processPayment( $_REQUEST['state_pol'], $_REQUEST['reference_sale'], $_REQUEST['sign'] );
        wp_send_json_success();
    }

}