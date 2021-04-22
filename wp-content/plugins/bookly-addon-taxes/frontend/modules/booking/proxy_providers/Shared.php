<?php
namespace BooklyTaxes\Frontend\Modules\Booking\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\Booking\Proxy;
use BooklyTaxes\Lib;
use BooklyTaxes\Lib\Entities;

/**
 * Class Shared
 * @package BooklyTaxes\Frontend\Modules\Booking\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function prepareCartItemInfoText( $data, BooklyLib\CartItem $cart_item )
    {
        $rates = array();
        // For WC orders, check if exists service.
        if ( $cart_item->getService() ) {
            $taxes = Lib\ProxyProviders\Local::getTaxRates();
            foreach ( $taxes[ $cart_item->getService()->getId() ] as $tax_id => $rate ) {
                $rates[] = Entities\Tax::find( $tax_id )->getTranslatedTitle() . ' ' . $rate . '%';
            }
            $data['service_tax'][] = BooklyLib\Utils\Price::format( Lib\ProxyProviders\Local::getServiceTaxAmount( $cart_item ) );
        } else {
            $data['service_tax'][] = '';
        }
        $data['service_tax_rate'][] = implode( ', ', $rates );

        return $data;
    }

    /**
     * @inheritDoc
     */
    public static function prepareInfoTextCodes( array $info_text_codes, array $data )
    {
        $info_text_codes['{service_tax}']        = isset( $data['service_tax'] ) ? '<b>' . implode( ', ', $data['service_tax'] ) . '</b>' : '';
        $info_text_codes['{service_tax_rate}']   = isset( $data['service_tax_rate'] ) ? '<b>' . implode( '; ', $data['service_tax_rate'] ) . '</b>' : '';
        if ( isset( $data['_cart_info'] ) ) {
            /** @var BooklyLib\CartInfo $cart_info */
            $cart_info = $data['_cart_info'];

            $info_text_codes['{total_tax}']          = '<b>' . BooklyLib\Utils\Price::format( $cart_info->getTotalTax() ) . '</b>';
            $info_text_codes['{total_price_no_tax}'] = '<b>' . BooklyLib\Utils\Price::format( $cart_info->getTotalNoTax() ) . '</b>';
        }

        return $info_text_codes;
    }
}