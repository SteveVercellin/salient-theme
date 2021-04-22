<?php
namespace Bookly2checkout\Frontend\Modules\TwoCheckout;

use Bookly\Lib as BooklyLib;
use Bookly2checkout\Lib;

/**
 * Class Controller
 * @package Bookly\Frontend\Modules\TwoCheckout
 */
class Controller extends BooklyLib\Base\Component
{
    /**
     * Approved.
     */
    public static function approved()
    {
        $userData = new BooklyLib\UserBookingData( self::parameter( 'bookly_fid' ) );
        if ( ( $redirect_url = self::parameter( 'x_receipt_link_url', false ) ) === false ) {
            // Clean GET parameters from 2Checkout.
            $redirect_url = remove_query_arg( Lib\Payment\TwoCheckout::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() );
        }
        if ( $userData->load() ) {
            $cart_info = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_2CHECKOUT );
            $cart_info->setGatewayTaxCalculationRule( 'tax_increases_the_cost' );

            $amount = number_format( $cart_info->getPayNow(), 2, '.', '' );
            $compare_key = strtoupper( md5( get_option( 'bookly_2checkout_api_secret_word' ) . get_option( 'bookly_2checkout_api_seller_id' ) . self::parameter( 'order_number' ) . $amount ) );
            if ( $compare_key != self::parameter( 'key' ) ) {
                header( 'Location: ' . wp_sanitize_redirect( add_query_arg( array(
                        'bookly_action' => '2checkout-error',
                        'bookly_fid' => self::parameter( 'bookly_fid' ),
                        'error_msg'  => urlencode( __( 'Invalid token provided', 'bookly' ) ),
                    ), BooklyLib\Utils\Common::getCurrentPageURL()
                ) ) );
                exit;
            } else {
                $coupon = $userData->getCoupon();
                if ( $coupon ) {
                    $coupon->claim();
                    $coupon->save();
                }
                $payment = new BooklyLib\Entities\Payment();
                $payment->setType( BooklyLib\Entities\Payment::TYPE_2CHECKOUT )
                    ->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )
                    ->setCartInfo( $cart_info )
                    ->save();
                $order = $userData->save( $payment );
                $payment->setDetailsFromOrder( $order, $cart_info )->save();
                BooklyLib\Notifications\Cart\Sender::send( $order );

                $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_2CHECKOUT, 'success' );
                $userData->sessionSave();

                @wp_redirect( $redirect_url );
                exit;
            }
        } else {
            header( 'Location: ' . wp_sanitize_redirect( add_query_arg( array(
                    'bookly_action' => '2checkout-error',
                    'bookly_fid' => self::parameter( 'bookly_fid' ),
                    'error_msg'  => urlencode( __( 'Invalid session', 'bookly' ) ),
                ), $redirect_url
            ) ) );
            exit;
        }
    }

    /**
     * Error.
     */
    public static function error()
    {
        $userData = new BooklyLib\UserBookingData( self::parameter( 'bookly_fid' ) );
        $userData->load();
        $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_2CHECKOUT, 'error', self::parameter( 'error_msg' ) );
        @wp_redirect( remove_query_arg( Lib\Payment\TwoCheckout::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
        exit;
    }
}