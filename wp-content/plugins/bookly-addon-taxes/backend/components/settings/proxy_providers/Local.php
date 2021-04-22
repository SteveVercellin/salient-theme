<?php
namespace BooklyTaxes\Backend\Components\Settings\ProxyProviders;

use Bookly\Backend\Components\Settings\Proxy;

/**
 * Class Local
 * @package BooklyTaxes\Backend\Components\Settings
 */
class Local extends Proxy\Taxes
{
    /**
     * @inheritdoc
     */
    public static function renderHelpMessage()
    {
        self::renderTemplate( 'help_block' );
    }
}