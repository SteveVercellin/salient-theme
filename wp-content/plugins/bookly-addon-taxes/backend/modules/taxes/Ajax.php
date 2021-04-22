<?php
namespace BooklyTaxes\Backend\Modules\Taxes;

use Bookly\Lib as BooklyLib;
use BooklyTaxes\Lib;

/**
 * Class Ajax
 * @package BooklyTaxes\Backend\Modules\Taxes
 */
class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * Get list of tax.
     */
    public static function getTaxes()
    {
        $taxes = Lib\Entities\Tax::query( 't' )
            ->select( 't.id, t.title, t.rate' )
            ->fetchArray();

        wp_send_json_success( $taxes );
    }

    /**
     * Remove tax(s).
     */
    public static function deleteTaxes()
    {
        $taxes = array_map( 'intval', self::parameter( 'taxes', array() ) );
        Lib\Entities\Tax::query()->delete()->whereIn( 'id', $taxes )->execute();
        wp_send_json_success();
    }

}