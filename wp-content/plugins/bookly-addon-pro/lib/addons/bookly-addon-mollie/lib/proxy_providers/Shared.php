<?php
namespace BooklyMollie\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyMollie\Lib;
use BooklyMollie\Frontend\Modules\Mollie;

/**
 * Class Shared
 * @package BooklyMollie\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function handleRequestAction( $action )
    {
        if ( get_option( 'bookly_mollie_enabled' ) ) {
            switch ( $action ) {
                case 'mollie-checkout':
                    Mollie\Controller::checkout();
                    break;
                case 'mollie-response':
                    Mollie\Controller::response();
                    break;
                case 'mollie-ipn':
                    Lib\Payment\Mollie::ipn();
                    break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function showPaymentSpecificPrices( $show )
    {
        if ( ! $show && get_option( 'bookly_mollie_enabled' ) ) {
            return (float) get_option( 'bookly_mollie_increase' ) != 0 || (float) get_option( 'bookly_mollie_addition' ) != 0;
        }

        return $show;
    }

    /**
     * @inheritdoc
     */
    public static function getOutdatedUnpaidPayments( $payments )
    {
        $timeout = (int) get_option( 'bookly_mollie_timeout' );
        if ( $timeout ) {
            $rows = BooklyLib\Entities\Payment::query( 'p' )
                ->select( 'p.id, p.details' )
                ->where( 'p.type', BooklyLib\Entities\Payment::TYPE_MOLLIE )
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
    public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_MOLLIE && get_option( 'bookly_mollie_enabled' ) ) {
            $cart_info->setGateway( $gateway );
        }

        return $cart_info;
    }
}