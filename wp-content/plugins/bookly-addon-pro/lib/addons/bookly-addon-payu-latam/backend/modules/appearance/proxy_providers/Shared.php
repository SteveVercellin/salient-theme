<?php
namespace BooklyPayuLatam\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;

/**
 * Class Shared
 * @package BooklyPayuLatam\Backend\Modules\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function renderPaymentGatewaySelector()
    {
        Proxy\Pro::renderPaymentGatewaySelector( 'bookly_l10n_label_pay_payu_latam', 'PayU Latam', true );
    }

    /**
     * @inheritDoc
     */
    public static function prepareOptions( array $options_to_save, array $options )
    {
        $options_to_save = array_merge( $options_to_save, array_intersect_key( $options, array_flip( array (
            'bookly_l10n_label_pay_payu_latam',
            'bookly_l10n_label_ccard_code',
            'bookly_l10n_label_ccard_expire',
            'bookly_l10n_label_ccard_number',
        ) ) ) );

        return $options_to_save;
    }

}