<?php
namespace BooklyPayson\Backend\Modules\Payments\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Payments\Proxy;
use BooklyPayson\Lib;

/**
 * Class Shared
 * @package BooklyPayson\Backend\Modules\Payments
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function paymentSpecificPriceExists( $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_PAYSON && get_option( 'bookly_payson_enabled' ) ) {
            return get_option( 'bookly_payson_increase' ) != 0
                || get_option( 'bookly_payson_addition' ) != 0;
        }

        return $gateway;
    }
}