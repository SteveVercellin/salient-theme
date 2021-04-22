<?php
namespace BooklyMollie\Lib;

/**
 * Class Updates
 * @package BooklyMollie\Lib
 */
class Updater extends \Bookly\Lib\Base\Updater
{
    public function update_1_1()
    {
        add_option( 'bookly_mollie_increase', '0' );
        add_option( 'bookly_mollie_addition', '0' );
        add_option( 'bookly_mollie_timeout', '0' );
    }
}