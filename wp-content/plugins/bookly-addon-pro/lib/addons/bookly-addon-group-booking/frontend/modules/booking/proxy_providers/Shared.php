<?php
namespace BooklyGroupBooking\Frontend\Modules\Booking\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyGroupBooking\Lib;


/**
 * Class Controller
 * @package BooklyGroupBooking\Frontend\Modules\Booking
 */
class Shared extends \Bookly\Frontend\Modules\Booking\Proxy\Shared
{
    /**
     * Render number of persons control on Service step.
     */
    public static function renderChainItemTail()
    {
        self::renderTemplate( 'chain_item_tail' );
    }
}