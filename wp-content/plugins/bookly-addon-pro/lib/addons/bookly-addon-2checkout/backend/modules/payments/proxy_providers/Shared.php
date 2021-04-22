<?php
namespace Bookly2checkout\Backend\Modules\Payments\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Payments\Proxy;
use Bookly2checkout\Lib;

/**
 * Class Shared
 * @package Bookly2checkout\Backend\Modules\Payments\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function paymentSpecificPriceExists( $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_2CHECKOUT && get_option( 'bookly_2checkout_enabled' ) ) {
            return get_option( 'bookly_2checkout_increase' ) != 0
                || get_option( 'bookly_2checkout_addition' ) != 0;
        }

        return $gateway;
    }
}