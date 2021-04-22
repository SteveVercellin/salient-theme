<?php
namespace BooklyPayuLatam\Backend\Modules\Payments\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Payments\Proxy;
use BooklyPayuLatam\Lib;

/**
 * Class Shared
 * @package BooklyPayuLatam\Backend\Modules\Payments\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function paymentSpecificPriceExists( $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_PAYULATAM && get_option( 'bookly_payu_latam_enabled' ) ) {
            return get_option( 'bookly_payu_latam_increase' ) != 0
                || get_option( 'bookly_payu_latam_addition' ) != 0;
        }

        return $gateway;
    }
}