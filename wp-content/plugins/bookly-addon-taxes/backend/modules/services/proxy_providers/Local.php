<?php
namespace BooklyTaxes\Backend\Modules\Services\ProxyProviders;

use Bookly\Backend\Modules\Services\Proxy;
use BooklyTaxes\Lib\Entities;

/**
 * Class Local
 * @package BooklyTaxes\Backend\Modules\Services\ProxyProviders
 */
class Local extends Proxy\Taxes
{
    /**
     * @inheritdoc
     */
    public static function renderSubForm( array $service )
    {
        $taxes = Entities\Tax::query()->sortBy( 'title' )->fetchArray();
        $service_taxes = Entities\ServiceTax::query()->where( 'service_id', $service['id'] )->fetchCol( 'tax_id' );

        self::renderTemplate( 'sub_form', compact( 'service', 'taxes', 'service_taxes' ) );
    }
}