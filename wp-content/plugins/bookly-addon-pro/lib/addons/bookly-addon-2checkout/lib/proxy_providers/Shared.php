<?php
namespace Bookly2checkout\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly2checkout\Lib;
use Bookly2checkout\Frontend\Modules\TwoCheckout;

/**
 * Class Shared
 * @package Bookly2checkout\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function handleRequestAction( $action )
    {
        if ( get_option( 'bookly_2checkout_enabled' ) ) {
            switch ( $action ) {
                case '2checkout-approved':
                    TwoCheckout\Controller::approved();
                    break;
                case '2checkout-error':
                    TwoCheckout\Controller::error();
                    break;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function showPaymentSpecificPrices( $show )
    {
        if ( ! $show && get_option( 'bookly_2checkout_enabled' ) ) {
            return (float) get_option( 'bookly_2checkout_increase' ) != 0 || (float) get_option( 'bookly_2checkout_addition' ) != 0;
        }

        return $show;
    }

    /**
     * @inheritdoc
     */
    public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_2CHECKOUT && get_option( 'bookly_2checkout_enabled' ) ) {
            $cart_info->setGateway( $gateway );
        }

        return $cart_info;
    }
}