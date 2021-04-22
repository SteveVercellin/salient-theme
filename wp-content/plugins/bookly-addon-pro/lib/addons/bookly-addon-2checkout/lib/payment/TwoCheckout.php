<?php
namespace Bookly2checkout\Lib\Payment;

use Bookly\Lib as BooklyLib;
use Bookly2checkout\Lib\ProxyProviders\Shared;
/**
 * Class TwoCheckout
 */
class TwoCheckout
{
    // Array for cleaning 2Checkout request
    public static $remove_parameters = array( 'bookly_action', 'bookly_fid', 'error_msg', 'sid', 'card_holder_name', 'cart_tangible', 'cart_weight', 'city', 'country', 'credit_card_processed', 'currency_code', 'email', 'first_name', 'fixed', 'invoice_id', 'ip_country', 'key', 'lang', 'last_name', 'li_0_description', 'li_0_name', 'li_0_price', 'li_0_product_id', 'li_0_quantity', 'li_0_tangible', 'li_0_type', 'li_1_description', 'li_1_name', 'li_1_price', 'li_1_product_id', 'li_1_quantity', 'li_1_tangible', 'li_1_type', 'merchant_order_id', 'middle_initial', 'order_number', 'pay_method', 'phone', 'state', 'street_address', 'street_address2', 'total', 'type', 'x_receipt_link_url', 'zip', );

    public static function renderForm( $form_id, $page_url )
    {
        $userData = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            $first_name = $userData->getFirstName();
            // Check if defined First name
            if ( ! $first_name ) {
                $full_name = $userData->getFullName();
            } else {
                $full_name = trim( $first_name . ' ' . $userData->getLastName() );
            }
            $cart_info = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_2CHECKOUT );
            $cart_info->setGatewayTaxCalculationRule( 'tax_increases_the_cost' );

            $replacement = array(
                '%action%'             => get_option( 'bookly_2checkout_sandbox' ) == 1
                    ? 'https://sandbox.2checkout.com/checkout/purchase'
                    : 'https://www.2checkout.com/checkout/purchase',
                '%x_receipt_link_url%' => esc_attr( $page_url ),
                '%card_holder_name%'   => esc_attr( $full_name ),
                '%currency_code%'      => get_option( 'bookly_pmt_currency' ),
                '%email%'              => esc_attr( $userData->getEmail() ),
                '%form_id%'            => $form_id,
                '%gateway%'            => BooklyLib\Entities\Payment::TYPE_2CHECKOUT,
                '%name%'               => esc_attr( $userData->cart->getItemsTitle( 128, false ) ),
                '%price%'              => $cart_info->getGatewayAmount(),
                '%tax%'                => $cart_info->getGatewayTax(),
                '%seller_id%'          => get_option( 'bookly_2checkout_api_seller_id' ),
                '%back%'               => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_button_back' ),
                '%next%'               => BooklyLib\Utils\Common::getTranslatedOption( 'bookly_l10n_step_payment_button_next' ),
                '%align_class%'        => get_option( 'bookly_app_align_buttons_left' ) ? 'bookly-left' : 'bookly-right',
            );

            $form = '<form action="%action%" method="post" data-gateway="%gateway%">
                <input type="hidden" name="bookly_fid" value="%form_id%">
                <input type="hidden" name="card_holder_name" value="%card_holder_name%">
                <input type="hidden" name="currency_code" value="%currency_code%">
                <input type="hidden" name="email" value="%email%">
                <input type="hidden" name="bookly_action" value="2checkout-approved">
                <input type="hidden" name="li_0_name" value="%name%">
                <input type="hidden" name="li_0_price" value="%price%" class="bookly-js-payment-amount">
                <input type="hidden" name="li_0_quantity" value="1">
                <input type="hidden" name="li_0_tangible" value="N">
                <input type="hidden" name="li_0_type" value="product">';
            if ( get_option( 'bookly_2checkout_send_tax' ) ) {
                $form .= '
                <input type="hidden" name="li_1_type" value="tax">
                <input type="hidden" name="li_1_price" value="%tax%" class="bookly-js-payment-tax">';
            }
            $form .= '
                <input type="hidden" name="mode" value="2CO">
                <input type="hidden" name="sid" value="%seller_id%">
                <input type="hidden" name="x_receipt_link_url" value="%x_receipt_link_url%">
                <button class="bookly-back-step bookly-js-back-step bookly-btn ladda-button" data-style="zoom-in" style="margin-right: 10px;" data-spinner-size="40"><span class="ladda-label">%back%</span></button>
                <div class="%align_class%">
                    <button class="bookly-next-step bookly-js-next-step bookly-btn ladda-button" data-style="zoom-in" data-spinner-size="40"><span class="ladda-label">%next%</span></button>
                </div>
            </form>';

            echo strtr( $form, $replacement );
        }
    }

}