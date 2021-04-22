<?php
namespace BooklyTaxes\Backend\Modules\Services\ProxyProviders;

use Bookly\Backend\Modules\Services\Proxy;
use BooklyTaxes\Lib\Entities\ServiceTax;
use BooklyTaxes\Lib\Entities\Tax;

/**
 * Class Shared
 * @package BooklyTaxes\Backend\Modules\Services\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareUpdateService( array $data )
    {
        if ( array_key_exists( 'taxes', $data ) ) {
            $service_taxes = ServiceTax::query()->where( 'service_id', $data['id'] )->fetchCol( 'tax_id' );
            $delete        = array();
            foreach ( $data['taxes'] as $tax_id ) {
                if ( ! in_array( $tax_id, $service_taxes ) ) {
                    $service_tax = new ServiceTax();
                    $service_tax
                        ->setServiceId( $data['id'] )
                        ->setTaxId( $tax_id )
                        ->save();
                }
            }

            foreach ( $service_taxes as $tax_id ) {
                if ( ! in_array( $tax_id, $data['taxes'] ) ) {
                    $delete[] = $tax_id;
                }
            }

            ServiceTax::query( 'r' )
                ->delete()
                ->where( 'r.service_id', $data['id'] )
                ->whereIn( 'r.tax_id', $delete )
                ->execute();
        } else {
            ServiceTax::query( 'r' )
                ->delete()
                ->where( 'r.service_id', $data['id'] )
                ->execute();
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public static function duplicateService( $source_id, $target_id )
    {
        foreach ( ServiceTax::query()->where( 'service_id', $source_id )->fetchArray() as $record ) {
            $new_record = new ServiceTax( $record );
            $new_record->setId( null )->setServiceId( $target_id )->save();
        }
    }
}