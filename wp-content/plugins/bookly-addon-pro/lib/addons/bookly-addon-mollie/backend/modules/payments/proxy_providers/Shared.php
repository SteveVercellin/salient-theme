<?php
namespace BooklyMollie\Backend\Modules\Payments\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Payments\Proxy;
use BooklyMollie\Lib;

/**
 * Class Shared
 * @package BooklyMollie\Backend\Modules\Payments
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function paymentSpecificPriceExists( $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_MOLLIE && get_option( 'bookly_mollie_enabled' ) ) {
            return get_option( 'bookly_mollie_increase' ) != 0
                || get_option( 'bookly_mollie_addition' ) != 0;
        }

        return $gateway;
    }
}