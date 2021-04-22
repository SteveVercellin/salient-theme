<?php
namespace Bookly2checkout\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Modules\Settings\Proxy;
use Bookly2checkout\Lib;

/**
 * Class Shared
 * @package Bookly2checkout\Backend\Modules\Settings\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function preparePaymentGatewaySettings( $payment_data )
    {
        $payment_data[ Lib\Plugin::getSlug() ] = self::renderTemplate( 'payment_settings', array(), false );

        return $payment_data;
    }

    /**
     * @inheritdoc
     */
    public static function saveSettings( array $alert, $tab, array $params )
    {
        if ( $tab == 'payments' ) {
            $options = array(
                'bookly_2checkout_enabled',
                'bookly_2checkout_send_tax',
                'bookly_2checkout_api_secret_word',
                'bookly_2checkout_api_seller_id',
                'bookly_2checkout_sandbox',
                'bookly_2checkout_increase',
                'bookly_2checkout_addition',
            );
            foreach ( $options as $option_name ) {
                if ( array_key_exists( $option_name, $params ) ) {
                    update_option( $option_name, trim( $params[ $option_name ] ) );
                }
            }
        }

        return $alert;
    }
}