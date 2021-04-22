<?php
namespace BooklyGroupBooking\Backend\Components\Appearance\ProxyProviders;

use Bookly\Backend\Components\Appearance\Proxy;

/**
 * Class Shared
 * @package BooklyGroupBooking\Backend\Components\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareCodes( array $codes )
    {
        return array_merge( $codes, array(
            array( 'code' => 'number_of_persons', 'description' => __( 'number of persons', 'bookly' ) ),
        ) );
    }
}