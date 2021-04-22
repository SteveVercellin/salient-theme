<?php
namespace BooklyTaxes\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Updates
 * @package BooklyTaxes\Lib
 */
class Updater extends BooklyLib\Base\Updater
{
    public function update_1_7()
    {
        $this->upgradeCharsetCollate( array(
            'bookly_taxes',
            'bookly_service_taxes',
        ) );
    }

    public function update_1_4()
    {
        $this->alterTables( array(
            'bookly_taxes' => array( 'ALTER TABLE `%s` CHANGE COLUMN `rate` `rate` DECIMAL(10,3) NOT NULL DEFAULT \'0.000\'' ),
        ) );
    }

    public function update_1_2()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        // Rename tables.
        $tables = array(
            'service_taxes',
            'taxes',
        );
        $query = 'RENAME TABLE ';
        foreach ( $tables as $table ) {
            $query .= sprintf( '`%s` TO `%s`, ', $this->getTableName( 'ab_' . $table ), $this->getTableName( 'bookly_' . $table ) );
        }
        $query = substr( $query, 0, -2 );
        $wpdb->query( $query );

        delete_option( 'bookly_taxes_enabled' );
    }
}