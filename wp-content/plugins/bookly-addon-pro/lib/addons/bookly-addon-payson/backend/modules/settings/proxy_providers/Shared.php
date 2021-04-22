<?php
namespace BooklyPayson\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Modules\Settings\Proxy;
use BooklyPayson\Lib;

/**
 * Class Shared
 * @package BooklyPayson\Backend\Modules\Settings\ProxyProviders
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
                'bookly_payson_enabled',
                'bookly_payson_api_agent_id',
                'bookly_payson_api_key',
                'bookly_payson_sandbox',
                'bookly_payson_increase',
                'bookly_payson_addition',
                'bookly_payson_timeout',
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