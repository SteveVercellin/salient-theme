<?php
namespace BooklyGroupBooking\Backend\Modules\Staff\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Staff\Proxy;

/**
 * Class Shared
 * @package BooklyGroupBooking\Backend\Modules\Staff\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function renderStaffServiceLabels()
    {
        self::renderTemplate( 'staff_service_label' );
    }

    /**
     * @inheritdoc
     */
    public static function renderStaffService( $staff_id, $service, array $services_data, $attributes = array() )
    {
        $read_only = false;
        if ( ( $service->getType() == BooklyLib\Entities\Service::TYPE_PACKAGE )
             || ( isset( $attributes['read-only']['capacity'] ) && $attributes['read-only']['capacity'] ) ) {
            $read_only = true;
        }

        self::renderTemplate( 'staff_service', compact( 'service', 'services_data', 'read_only' ) );
    }
}