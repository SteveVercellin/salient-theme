<?php
namespace BooklyTaxes\Backend\Modules\Notifications\ProxyProviders;

use Bookly\Backend\Modules\Notifications\Proxy;

/**
 * Class Shared
 * @package BooklyTaxes\Backend\Modules\Notifications\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareNotificationCodes( array $codes, $type )
    {
        $codes['service']['service_tax'] = __( 'service tax amount', 'bookly' );
        $codes['service']['service_tax_rate'] = __( 'service tax rate', 'bookly' );
        $codes['service']['total_tax']   = __( 'total tax included in the appointment (summary for all items)', 'bookly' );
        $codes['service']['total_price_no_tax'] = __( 'total price without tax', 'bookly' );

        $codes['cart']['total_tax']   = __( 'total tax included in the appointment (summary for all items)', 'bookly' );
        $codes['cart']['total_price_no_tax'] = __( 'total price without tax', 'bookly' );

        $codes['series']['total_tax'] = __( 'total tax included in the appointment (summary for all items)', 'bookly' );
        $codes['series']['total_price_no_tax'] = __( 'total price without tax', 'bookly' );

        return $codes;
    }
}