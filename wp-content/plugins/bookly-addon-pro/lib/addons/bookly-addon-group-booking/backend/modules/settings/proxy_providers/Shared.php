<?php
namespace BooklyGroupBooking\Backend\Modules\Settings\ProxyProviders;

use Bookly\Backend\Modules\Settings\Proxy;
use Bookly\Backend\Components\Settings\Menu;

/**
 * Class Shared
 * @package BooklyGroupBooking\Backend\Modules\Settings\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function renderMenuItem()
    {
        Menu::renderItem( __( 'Group Booking', 'bookly' ), 'group_booking' );
    }

    /**
     * @inheritdoc
     */
    public static function renderTab()
    {
        self::renderTemplate( 'settings_tab' );
    }

    /**
     * @inheritdoc
     */
    public static function saveSettings( array $alert, $tab, array $params )
    {
        if ( $tab == 'group_booking' ) {
            $options = array( 'bookly_group_booking_nop_format' );
            foreach ( $options as $option_name ) {
                if ( array_key_exists( $option_name, $params ) ) {
                    update_option( $option_name, $params[ $option_name ] );
                }
            }
            $alert['success'][] = __( 'Settings saved.', 'bookly' );
        }

        return $alert;
    }

    /**
     * @inheritdoc
     */
    public static function prepareCalendarAppointmentCodes( array $codes, $participants )
    {
        if ( $participants == 'one' ) {
            $codes[] = array( 'code' => 'number_of_persons', 'description' => __( 'number of persons', 'bookly' ), );
        } else {
            $codes[] = array( 'code' => 'signed_up', 'description' => __( 'number of persons already in the list', 'bookly' ) );
            $codes[] = array( 'code' => 'service_capacity', 'description' => __( 'capacity of service', 'bookly' ) );
        }

        return $codes;
    }
}