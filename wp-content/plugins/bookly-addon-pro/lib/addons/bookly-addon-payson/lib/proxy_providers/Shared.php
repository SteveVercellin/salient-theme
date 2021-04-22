<?php
namespace BooklyPayson\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyPayson\Lib;
use BooklyPayson\Frontend\Modules\Payson;

/**
 * Class Shared
 * @package BooklyPayson\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function getOutdatedUnpaidPayments( $payments )
    {
        $timeout = (int) get_option( 'bookly_payson_timeout' );
        if ( $timeout ) {
            $rows = BooklyLib\Entities\Payment::query( 'p' )
                ->select( 'p.id, p.details' )
                ->where( 'p.type', BooklyLib\Entities\Payment::TYPE_PAYSON )
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
        if ( get_option( 'bookly_payson_enabled' ) ) {
            switch ( $action ) {
                // Payson.
                case 'payson-checkout':
                    Payson\Controller::checkout();
                    break;
                case 'payson-confirm':
                    Payson\Controller::confirm();
                    break;
                case 'payson-ipn':
                    Lib\Payment\Payson::ipn();
                    break;
            }
        }

    }

    /**
     * @inheritdoc
     */
    public static function showPaymentSpecificPrices( $show )
    {
        if ( ! $show && get_option( 'bookly_payson_enabled' ) ) {
            return (float) get_option( 'bookly_payson_increase' ) != 0 || (float) get_option( 'bookly_payson_addition' ) != 0;
        }

        return $show;
    }

    /**
     * @inheritdoc
     */
    public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_PAYSON && get_option( 'bookly_payson_enabled' ) ) {
            $cart_info->setGateway( $gateway );
        }

        return $cart_info;
    }
}