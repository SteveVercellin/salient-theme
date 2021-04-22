<?php
namespace BooklyPayuLatam\Frontend\Modules\PayuLatam;

use Bookly\Lib as BooklyLib;
use BooklyPayuLatam\Lib\Payment;

/**
 * Class Controller
 * @package BooklyPayuLatam\Frontend\Modules\PayuLatam
 */
class Controller extends BooklyLib\Base\Component
{
    /**
     * Checkout.
     */
    public static function checkout()
    {
        $transaction_state = self::parameter( 'transactionState' );
        if ( false === Payment\PayuLatam::processPayment( $transaction_state, self::parameter( 'referenceCode' ), self::parameter( 'signature' ) ) ) {
            switch ( $transaction_state ) {
                case 6:
                    $message = __( 'Transaction rejected', 'bookly' );
                    break;
                case 104:
                    $message = __( 'Error', 'bookly' );
                    break;
                case 7:
                    $message = __( 'Pending payment', 'bookly' );
                    break;
                default:
                    $message = self::parameter( 'message' ) . ' ' . __( 'Invalid token provided', 'bookly' );
                    break;
            }
            header( 'Location: ' . wp_sanitize_redirect( add_query_arg( array(
                    'bookly_action' => 'payu_latam-error',
                    'bookly_fid' => self::parameter( 'bookly_fid' ),
                    'error_msg'  => urlencode( $message ),
                ), BooklyLib\Utils\Common::getCurrentPageURL()
                ) ) );
            exit;
        } else {
            // Clean GET parameters from PayU Latam.
            $userData = new BooklyLib\UserBookingData( stripslashes( self::parameter( 'bookly_fid' ) ) );
            $userData->load();
            $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_PAYULATAM, 'success' );
            $userData->sessionSave();

            @wp_redirect( remove_query_arg( Payment\PayuLatam::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
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
        $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_PAYULATAM, 'error', self::parameter( 'error_msg' ) );
        $userData->sessionSave();

        @wp_redirect( remove_query_arg( Payment\PayuLatam::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
        exit;
    }
}