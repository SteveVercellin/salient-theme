<?php
namespace Bookly\Backend\Components\Dialogs\TableSettings;

use Bookly\Lib;

/**
 * Class Dialog
 * @package Bookly\Backend\Components\Dialogs\TableSettings
 */
class Dialog extends Lib\Base\Component
{
    /**
     * Render notifications queue dialog.
     */
    public static function render()
    {
        self::enqueueStyles( array(
            'frontend' => array( 'css/ladda.min.css', ),
            'backend'  => array( 'css/fontawesome-all.min.css' ),
        ) );

        self::enqueueScripts( array(
            'backend'  => array(
                'js/sortable.min.js' => array( 'jquery' ),
            ),
            'frontend' => array(
                'js/spin.min.js'  => array( 'jquery' ),
                'js/ladda.min.js' => array( 'jquery' ),
            ),
            'module'   => array( 'js/table-settings-dialog.js' => array( 'jquery', 'bookly-sortable.min.js' ) ),
        ) );

        wp_localize_script( 'bookly-table-settings-dialog.js', 'BooklyTableSettingsDialogL10n', array(
            'csrfToken' => Lib\Utils\Common::getCsrfToken(),
        ) );

        self::renderTemplate( 'dialog' );
    }

    /**
     * Render 'settings' button
     *
     * @param string $table_name
     * @param string $setting_name
     * @param string $location
     */
    public static function renderButton( $table_name, $setting_name = 'BooklyL10n', $location = '' )
    {
        self::renderTemplate( 'button', compact( 'table_name', 'setting_name', 'location' ) );
    }
}