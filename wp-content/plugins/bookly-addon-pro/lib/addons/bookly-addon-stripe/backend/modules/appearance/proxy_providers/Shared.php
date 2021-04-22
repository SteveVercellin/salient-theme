<?php
namespace BooklyStripe\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;
use BooklyStripe\Lib\Plugin;

/**
 * Class Shared
 * @package BooklyStripe\Backend\Modules\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function renderPaymentGatewaySelector()
    {
        Proxy\Pro::renderPaymentGatewaySelector( 'bookly_l10n_label_pay_stripe', 'Stripe', false );
    }

    /**
     * @inheritDoc
     */
    public static function prepareOptions( array $options_to_save, array $options )
    {
        $options_to_save = array_merge( $options_to_save, array_intersect_key( $options, array_flip( array (
            'bookly_l10n_label_pay_stripe',
        ) ) ) );

        return $options_to_save;
    }
}