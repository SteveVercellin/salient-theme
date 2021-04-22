<?php
namespace BooklyTaxes\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Installer
 * @package BooklyTaxes\Lib
 */
class Installer extends Base\Installer
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->options = array(
            'bookly_taxes_in_price' => 'excluded'
        );
    }

    /**
     * Create tables in database.
     */
    public function createTables()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        $charset_collate = $wpdb->has_cap( 'collation' )
            ? $wpdb->get_charset_collate()
            : 'DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci';

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . Entities\Tax::getTableName() . '` (
                `id`    INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(255) DEFAULT "",
                `rate`  DECIMAL(10,3) NOT NULL DEFAULT 0.000
            ) ENGINE = INNODB
            ' . $charset_collate
        );

        $wpdb->query(
            'CREATE TABLE IF NOT EXISTS `' . Entities\ServiceTax::getTableName() . '` (
                `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `service_id` INT UNSIGNED NOT NULL,
                `tax_id`     INT UNSIGNED NOT NULL,
                CONSTRAINT
                    FOREIGN KEY (service_id)
                    REFERENCES ' . BooklyLib\Entities\Service::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE,
                CONSTRAINT 
                    FOREIGN KEY (tax_id)
                    REFERENCES ' . Entities\Tax::getTableName() . '(id)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
                ) ENGINE = INNODB
                ' . $charset_collate
        );
    }

    /**
     * @inheritdoc
     */
    public function removeData()
    {
        /** @global \wpdb $wpdb */
        global $wpdb;

        parent::removeData();

        // Remove user meta.
        $meta_names = array(
            $this->getPrefix() . 'table_settings',
        );
        $wpdb->query( $wpdb->prepare( sprintf( 'DELETE FROM `' . $wpdb->usermeta . '` WHERE meta_key IN (%s)',
            implode( ', ', array_fill( 0, count( $meta_names ), '%s' ) ) ), $meta_names ) );
    }
}