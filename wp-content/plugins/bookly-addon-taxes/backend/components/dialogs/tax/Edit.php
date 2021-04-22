<?php
namespace BooklyTaxes\Backend\Components\Dialogs\Tax;

use Bookly\Lib as BooklyLib;

/**
 * Class Edit
 * @package BooklyTaxes\Backend\Components\Dialogs\Taxes
 */
class Edit extends BooklyLib\Base\Component
{
    public static function render()
    {
        self::renderTemplate( 'edit' );
    }
}