<?php
namespace BooklyTaxes\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyTaxes\Lib;
use BooklyTaxes\Backend\Modules\Taxes\Page;

/**
 * Class Local
 * @package BooklyTaxes\Lib\ProxyProviders
 */
class Local extends BooklyLib\Proxy\Taxes
{
    /**
     * @inheritDoc
     */
    public static function addBooklyMenuItem()
    {
        add_submenu_page(
            'bookly-menu',
            __( 'Taxes', 'bookly' ),
            __( 'Taxes', 'bookly' ),
            BooklyLib\Utils\Common::getRequiredCapability(),
            Page::pageSlug(),
            function () { Page::render(); }
        );
    }

    /**
     * @inheritDoc
     */
    public static function getItemTaxAmount( BooklyLib\CartItem $cart_item  )
    {
        $rates = self::getServiceTaxRates();

        return Lib\Utils\Common::calculateTheAmountOfTax( $cart_item->getServicePrice( $cart_item->getNumberOfPersons() ), $rates[ $cart_item->getServiceId() ] );
    }

    /**
     * @inheritDoc
     */
    public static function getServiceTaxAmount( BooklyLib\CartItem $cart_item  )
    {
        $rates = self::getServiceTaxRates();

        $price = $cart_item->getServicePriceWithoutExtras();

        return Lib\Utils\Common::calculateTheAmountOfTax( $price * $cart_item->getNumberOfPersons(), $rates[ $cart_item->getServiceId() ] );
    }

    /**
     * @inheritDoc
     */
    public static function calculateTax( $amount, $rate )
    {
        return Lib\Utils\Common::calculateTheAmountOfTax( $amount, $rate );
    }

    /**
     * @inheritDoc
     */
    public static function prepareTaxRateAmounts( array $amounts, BooklyLib\CartItem $cart_item, $allow_coupon )
    {
        if ( ! $cart_item->toBePutOnWaitingList() ) {
            $rates = self::getServiceTaxRates();
            $amounts[] = array(
                'rate'         => $rates[ $cart_item->getServiceId() ],
                'allow_coupon' => $allow_coupon,
                'deposit'      => $cart_item->getDepositPrice(),
                'total'        => $cart_item->getServicePrice( $cart_item->getNumberOfPersons() ),
            );
        }

        return $amounts;
    }

    /**
     * @inheritDoc
     */
    public static function getServiceTaxRates()
    {
        $rates = self::getFromCache( 'rates', null );
        if ( $rates === null ) {
            $rows = BooklyLib\Entities\Service::query('s' )
                ->leftJoin( 'ServiceTax', 'st', 'st.service_id = s.id', '\BooklyTaxes\Lib\Entities' )
                ->leftJoin( 'Tax', 't', 't.id = st.tax_id', '\BooklyTaxes\Lib\Entities' )
                ->select( 'COALESCE(SUM(t.rate), 0) AS rate, s.id AS service_id' )
                ->groupBy( 's.id' )
                ->fetchArray();
            $rates = array();
            foreach ( $rows as $row ) {
                $rates[ $row['service_id'] ] = $row['rate'];
            }

            self::putInCache( 'rates', $rates );
        }

        return $rates;
    }

    /**
     * Returns rate for all services.
     *
     * @return array
     */
    public static function getTaxRates()
    {
        $rates = self::getFromCache( 'taxes', null );
        if ( $rates === null ) {
            $rows = BooklyLib\Entities\Service::query('s' )
                ->leftJoin( 'ServiceTax', 'st', 'st.service_id = s.id', '\BooklyTaxes\Lib\Entities' )
                ->leftJoin( 'Tax', 't', 't.id = st.tax_id', '\BooklyTaxes\Lib\Entities' )
                ->select( 't.rate, s.id AS service_id, t.id AS tax_id' )
                ->fetchArray();

            $custom_service_id = null;
            //               Custom service without taxes
            $rates = array( $custom_service_id => array() );
            foreach ( $rows as $row ) {
                if ( ! array_key_exists( $row['service_id'], $rates ) ) {
                    $rates[ $row['service_id'] ] = array();
                }
                if ( $row['rate'] !== null ) {
                    $rates[ $row['service_id'] ][ $row['tax_id'] ] = (float) $row['rate'];
                }
            }

            self::putInCache( 'taxes', $rates );
        }

        return $rates;
    }

    /**
     * @inheritDoc
     */
    public static function showTaxColumn()
    {
        // Show tax column when the tax amount is excluded from price.
        return get_option( 'bookly_taxes_in_price' ) == Lib\Entities\Tax::TAX_EXCLUDED;
    }
}