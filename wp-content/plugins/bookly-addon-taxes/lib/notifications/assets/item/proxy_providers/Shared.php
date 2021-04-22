<?php
namespace BooklyTaxes\Lib\Notifications\Assets\Item\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Lib\Notifications\Assets\Item\Codes;
use Bookly\Lib\Notifications\Assets\Item\Proxy;
use BooklyTaxes\Lib\Entities\Tax;
use BooklyTaxes\Lib\ProxyProviders\Local;

/**
 * Class Shared
 * @package BooklyTaxes\Lib\Notifications\Assets\Item\ProxyProviders
 */
abstract class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareCodes( Codes $codes )
    {
        $taxes = Local::getTaxRates();
        $codes->service_tax      = array();
        $codes->service_tax_rate = array();
        foreach ( $codes->getOrder()->getItems() as $item ) {
            $codes->service_tax[] = BooklyLib\Utils\Price::format( $item->getTax() );
            $rates = array();
            foreach ( $taxes[ $item->getService()->getId() ] as $tax_id => $rate ) {
                $rates[] = Tax::find( $tax_id )->getTranslatedTitle() . ' ' . $rate . '%';
            }

            $codes->service_tax_rate[] = implode( ', ', $rates );
        }
    }

    /**
     * @inheritdoc
     */
    public static function prepareReplaceCodes( array $replace_codes, Codes $codes, $format )
    {
        $replace_codes['{service_tax}']      = $codes->service_tax !== null ? implode( ', ', $codes->service_tax ) : '';
        $replace_codes['{service_tax_rate}'] = $codes->service_tax_rate !== null ? implode( '; ', $codes->service_tax_rate ) : '';

        return $replace_codes;
    }
}