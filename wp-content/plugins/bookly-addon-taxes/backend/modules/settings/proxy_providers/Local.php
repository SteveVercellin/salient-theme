<?php
namespace BooklyTaxes\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Modules\Settings\Proxy;
use Bookly\Backend\Components\Settings\Menu;

/**
 * Class Local
 * @package BooklyTaxes\Backend\Modules\Settings\ProxyProviders
 */
class Local extends Proxy\Taxes
{
    /**
     * @inheritdoc
     */
    public static function renderPayments()
    {
        self::renderTemplate( 'taxes_in_price' );
    }
}