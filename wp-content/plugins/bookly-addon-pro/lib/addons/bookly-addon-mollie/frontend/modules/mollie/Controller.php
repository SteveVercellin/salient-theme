<?php
namespace BooklyMollie\Frontend\Modules\Mollie;

use Bookly\Lib as BooklyLib;
use BooklyMollie\Lib;

/**
 * Class Controller
 * @package Bookly\Frontend\Modules\Mollie
 */
class Controller extends BooklyLib\Base\Component
{
    /**
     * Checkout.
     */
    public static function checkout()
    {
        $form_id  = self::parameter( 'bookly_fid' );
        $userData = new BooklyLib\UserBookingData( $form_id );
        if ( $userData->load() ) {
            Lib\Payment\Mollie::paymentPage( $form_id, $userData, self::parameter( 'response_url' ) );
        }
    }

    /**
     * Redirect from Payment Form to Bookly page.
     */
    public static function response()
    {
        $form_id  = self::parameter( 'bookly_fid' );
        $userData = new BooklyLib\UserBookingData( $form_id );
        $userData->load();
        if ( $payment = BooklyLib\Session::getFormVar( $form_id, 'payment' ) ) {
            if ( $payment['status'] == 'pending' ) {
                $mollie_payment = Lib\Payment\Mollie::getPayment( $payment['data'] );
                if ( $mollie_payment->isOpen() || $mollie_payment->isPending() || $mollie_payment->isPaid() ) {
                    // Payment processing
                    $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_MOLLIE, 'processing' );
                    @wp_redirect( remove_query_arg( Lib\Payment\Mollie::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
                } else {
                    // Customer cancel payment
                    /** @var BooklyLib\Entities\CustomerAppointment $ca */
                    foreach ( BooklyLib\Entities\CustomerAppointment::query()->where( 'payment_id', $mollie_payment->metadata->payment_id )->find() as $ca ) {
                        $ca->deleteCascade();
                    }
                    BooklyLib\Entities\Payment::query()->delete()->where( 'type', BooklyLib\Entities\Payment::TYPE_MOLLIE )
                        ->where( 'id', $mollie_payment->metadata->payment_id )
                        ->execute();
                    $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_MOLLIE, 'cancelled' );
                    $userData->sessionSave();

                    @wp_redirect( remove_query_arg( Lib\Payment\Mollie::$remove_parameters, BooklyLib\Utils\Common::getCurrentPageURL() ) );
                }
            }
        }
        exit;
    }
}