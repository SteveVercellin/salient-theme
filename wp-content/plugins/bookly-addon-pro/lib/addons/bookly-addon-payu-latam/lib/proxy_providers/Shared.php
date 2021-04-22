<?php
namespace BooklyPayuLatam\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyPayuLatam\Lib;
use BooklyPayuLatam\Frontend\Modules\PayuLatam;

/**
 * Class Shared
 * @package BooklyPayuLatam\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function getOutdatedUnpaidPayments( $payments )
    {
        $timeout = (int) get_option( 'bookly_payu_latam_timeout' );
        if ( $timeout ) {
            $rows = BooklyLib\Entities\Payment::query( 'p' )
                ->select( 'p.id, p.details' )
                ->where( 'p.type', BooklyLib\Entities\Payment::TYPE_PAYULATAM )
                ->where( 'p.status', BooklyLib\Entities\Payment::STATUS_PENDING )
                ->whereLt( 'p.created', date_create( current_time( 'mysql' ) )->modify( sprintf( '- %s seconds', $timeout ) )->format( 'Y-m-d H:i:s' ) )
                ->fetchArray();
            foreach ( $rows as $row ) {
                $payments[ $row['id'] ] = $row['details'];
            }
        }

        return $payments;
    }

    /**
     * @inheritdoc
     */
    public static function handleRequestAction( $action )
    {
        switch ( $action ) {
            case 'payu_latam-checkout':
                PayuLatam\Controller::checkout();
                break;
            case 'payu_latam-ipn':
                Lib\Payment\PayuLatam::ipn();
                break;
            case 'payu_latam-error':
                PayuLatam\Controller::error();
                break;
        }
    }

    /**
     * @inheritdoc
     */
    public static function showPaymentSpecificPrices( $show )
    {
        if ( ! $show && get_option( 'bookly_payu_latam_enabled' ) ) {
            return (float) get_option( 'bookly_payu_latam_increase' ) != 0 || (float) get_option( 'bookly_payu_latam_addition' ) != 0;
        }

        return $show;
    }

    /**
     * @inheritdoc
     */
    public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_PAYULATAM && get_option( 'bookly_payu_latam_enabled' ) ) {
            $cart_info->setGateway( $gateway );
        }

        return $cart_info;
    }
}