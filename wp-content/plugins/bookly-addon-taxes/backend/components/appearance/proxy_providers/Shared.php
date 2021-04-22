<?php
namespace BooklyTaxes\Backend\Components\Appearance\ProxyProviders;

use Bookly\Backend\Components\Appearance\Proxy;

/**
 * Class Shared
 * @package BooklyTaxes\Frontend\Modules\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareCodes( array $codes )
    {
        return array_merge( $codes, array(
            array( 'code' => 'service_tax',      'description' => __( 'service tax amount', 'bookly' ),          'flags' => array( 'step' => '>=5' ) ),
            array( 'code' => 'service_tax_rate', 'description' => __( 'service tax rate', 'bookly' ),            'flags' => array( 'step' => '>=5' ) ),
            array( 'code' => 'total_tax',        'description' => __( 'total tax included in the appointment (summary for all items)', 'bookly' ), 'flags' => array( 'step' => '>=5' ) ),
            array( 'code' => 'total_price_no_tax', 'description' => __( 'total price without tax', 'bookly' ),   'flags' => array( 'step' => '>=5' ) ),
        ) );
    }
}