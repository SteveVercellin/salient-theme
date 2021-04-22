<?php
namespace BooklyMollie\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;

/**
 * Class Shared
 * @package BooklyMollie\Backend\Modules\Settings\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareOptions( array $options_to_save, array $options )
    {
        $options_to_save = array_merge( $options_to_save, array_intersect_key( $options, array_flip( array (
            'bookly_l10n_label_pay_mollie',
        ) ) ) );

        return $options_to_save;
    }

    /**
     * @inheritdoc
     */
    public static function renderPaymentGatewaySelector()
    {
        self::renderTemplate( 'gateway_selector' );
    }
}