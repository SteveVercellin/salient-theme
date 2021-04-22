<?php
namespace BooklyStripe\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyStripe\Lib\Plugin;
use BooklyStripe\Backend\Components\Notices;

/**
 * Class Shared
 * @package BooklyStripe\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function showPaymentSpecificPrices( $show )
    {
        if ( ! $show && get_option( 'bookly_stripe_enabled' ) ) {
            return (float) get_option( 'bookly_stripe_increase' ) != 0 || (float) get_option( 'bookly_stripe_addition' ) != 0;
        }

        return $show;
    }

    /**
     * @inheritdoc
     */
    public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_STRIPE && get_option( 'bookly_stripe_enabled' ) ) {
            $cart_info->setGateway( $gateway );
        }

        return $cart_info;
    }

    /**
     * @inheritdoc
     */
    public static function getOutdatedUnpaidPayments( $payments )
    {
        $timeout = (int) get_option( 'bookly_stripe_timeout' );
        if ( $timeout ) {
            $rows = BooklyLib\Entities\Payment::query( 'p' )
                ->select( 'p.id, p.details' )
                ->where( 'p.type', BooklyLib\Entities\Payment::TYPE_STRIPE )
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
    public static function renderAdminNotices( $bookly_page )
    {
        Notices\ScaUpdate::render();
    }
}