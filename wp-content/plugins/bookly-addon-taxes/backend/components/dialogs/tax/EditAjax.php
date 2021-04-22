<?php
namespace BooklyTaxes\Backend\Components\Dialogs\Tax;

use Bookly\Lib as BooklyLib;

/**
 * Class EditAjax
 * @package BooklyTaxes\Backend\Components\Dialogs\Taxes
 */
class EditAjax extends BooklyLib\Base\Ajax
{
    /**
     * Add new tax.
     */
    public static function saveTax()
    {
        $form = new Forms\Tax();
        $form->bind( self::postParameters() );
        $tax = $form->save();

        wp_send_json_success( $tax->getFields() );
    }
}