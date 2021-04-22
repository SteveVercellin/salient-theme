<?php
namespace BooklyTaxes\Backend\Components\Dialogs\Tax\Forms;

/**
 * Class Tax
 * @package BooklyTaxes\Forms
 */
class Tax extends \Bookly\Lib\Base\Form
{
    protected static $entity_class = 'Tax';

    protected static $namespace = '\BooklyTaxes\Lib\Entities';

    public function configure()
    {
        $this->setFields( array( 'id', 'title', 'rate' ) );
    }
}