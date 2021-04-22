<?php
namespace BooklyGroupBooking\Backend\Modules\Services\ProxyProviders;

use Bookly\Backend\Modules\Services\Proxy;

/**
 * Class Shared
 * @package BooklyGroupBooking\Backend\Modules\Services
 */
class Local extends Proxy\GroupBooking
{
    /**
     * @inheritdoc
     */
    public static function renderSubForm( array $service )
    {
        self::renderTemplate( 'sub_form', compact( 'service' ) );
    }
}