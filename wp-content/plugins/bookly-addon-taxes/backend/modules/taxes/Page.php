<?php
namespace BooklyTaxes\Backend\Modules\Taxes;

use Bookly\Lib as BooklyLib;

/**
 * Class Page
 * @package BooklyTaxes\Backend\Modules\Taxes
 */
class Page extends BooklyLib\Base\Component
{
    /**
     * Render page.
     */
    public static function render()
    {
        self::enqueueStyles( array(
            'bookly' => array(
                'backend/resources/bootstrap/css/bootstrap.min.css',
                'frontend/resources/css/ladda.min.css',
            ),
        ) );

        self::enqueueScripts( array(
            'bookly' => array(
                'backend/resources/bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'backend/resources/js/datatables.min.js'  => array( 'jquery' ),
                'frontend/resources/js/spin.min.js' => array( 'jquery' ),
                'frontend/resources/js/ladda.min.js' => array( 'jquery' ),
            ),
            'module' => array( 'js/tax.js' => array( 'jquery' ), ),
        ) );

        $datatables = BooklyLib\Utils\Tables::getSettings( 'taxes' );

        wp_localize_script( 'bookly-tax.js', 'BooklyTaxesL10n', array(
            'csrfToken'      => BooklyLib\Utils\Common::getCsrfToken(),
            'edit'           => esc_attr__( 'Edit', 'bookly' ),
            'title'          => array(
                'new'  => esc_attr__( 'New tax', 'bookly' ),
                'edit' => esc_attr__( 'Edit tax', 'bookly' ),
            ),
            'are_you_sure'   => esc_attr__( 'Are you sure?', 'bookly' ),
            'zeroRecords'    => esc_attr__( 'No taxes found.', 'bookly' ),
            'processing'     => esc_attr__( 'Processing...', 'bookly' ),
            'datatables' => $datatables,
        ) );

        self::renderTemplate( 'index', compact( 'datatables' ) );
    }
}