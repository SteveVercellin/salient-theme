<?php
namespace BooklyAuthorizeNet\Backend\Modules\Payments\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Payments\Proxy;
use BooklyAuthorizeNet\Lib;

/**
 * Class Shared
 * @package BooklyAuthorizeNet\Backend\Modules\Payments\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function paymentSpecificPriceExists( $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_AUTHORIZENET && get_option( 'bookly_authorize_net_enabled' ) ) {
            return get_option( 'bookly_authorize_net_increase' ) != 0
                || get_option( 'bookly_authorize_net_addition' ) != 0;
        }

        return $gateway;
    }
}