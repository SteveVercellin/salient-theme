<?php
namespace BooklyPayson\Frontend\Modules\Payson;

use BooklyPayson\Lib;
use Bookly\Lib as BooklyLib;

/**
 * Class Controller
 * @package BooklyPayson\Frontend\Modules\Payson
 */
class Controller extends BooklyLib\Base\Component
{
    /**
     * Bookly checkout.
     */
    public static function checkout()
    {
        $form_id  = self::parameter( 'bookly_fid' );
        $userData = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            Lib\Payment\Payson::paymentPage( $form_id, $userData, self::parameter( 'response_url' ) );
        }
    }

    /**
     * Confirm payment.
     */
    public static function confirm()
    {
        $form_id     = self::parameter( 'bookly_fid' );
        $checkout_id = BooklyLib\Session::getFormVar( $form_id, 'payson.checkout_id' );
        $userData    = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            switch ( Lib\Payment\Payson::confirm( $checkout_id ) ) {
                case Lib\Payment\Payson::STATUS_SUCCESS:
                    $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_PAYSON, 'success' );
                    $userData->sessionSave();
                    @wp_redirect( remove_query_arg( Lib\Payment\Payson::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
                    exit;
                case Lib\Payment\Payson::STATUS_INVALID:
                case Lib\Payment\Payson::STATUS_DENIED:
                    $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_PAYSON, 'error',  __( 'Incorrect payment data', 'bookly' ) );
                    $userData->sessionSave();
                    @wp_redirect( remove_query_arg( Lib\Payment\Payson::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
                    exit;
                case Lib\Payment\Payson::STATUS_PROCESSING:
                    $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_PAYSON, 'processing' );
                    $userData->sessionSave();
                    @wp_redirect( remove_query_arg( Lib\Payment\Payson::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
                    exit;
            }
        }
    }
}