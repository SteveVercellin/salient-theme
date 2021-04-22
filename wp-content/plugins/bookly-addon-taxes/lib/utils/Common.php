<?php
namespace BooklyTaxes\Lib\Utils;

use BooklyTaxes\Lib\Entities\Tax;

/**
 * Class Common
 * @package BooklyTaxes\Lib\Utils
 */
abstract class Common
{
    /**
     * Calculate the amount of tax.
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    public static function calculateTheAmountOfTax( $amount, $rate = 0.0 )
    {
        switch ( get_option( 'bookly_taxes_in_price' ) ) {
            case Tax::TAX_INCLUDED:
                return self::getIncludedTax( $amount, $rate );
            case Tax::TAX_EXCLUDED:
            default:
                return self::getAddedTax( $amount, $rate );
        }
    }

    /**
     * Calculate the tax that's included in the price.
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    private static function getIncludedTax( $amount, $rate = 0.0 )
    {
        return round( $amount / ( 100.0 + $rate ) * $rate, 2 );
    }

    /**
     * Calculate the tax that will be added to the final price.
     *
     * @param float $amount
     * @param float $rate
     * @return float
     */
    private static function getAddedTax( $amount, $rate = 0.0 )
    {
        return $amount / 100.0 * $rate;
    }

}