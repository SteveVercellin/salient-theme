<?php
namespace BooklyTaxes\Lib;

use Bookly\Lib as BooklyLib;
use BooklyTaxes\Backend\Components;
use BooklyTaxes\Backend\Modules as Backend;
use BooklyTaxes\Frontend\Modules as Frontend;

/**
 * Class Plugin
 * @package BooklyTaxes\Lib
 */
abstract class Plugin extends BooklyLib\Base\Plugin
{
    protected static $prefix;
    protected static $title;
    protected static $version;
    protected static $slug;
    protected static $directory;
    protected static $main_file;
    protected static $basename;
    protected static $text_domain;
    protected static $root_namespace;
    protected static $embedded;

    /**
     * @inheritdoc
     */
    protected static function init()
    {
        // Init ajax.
        Backend\Taxes\Ajax::init();
        Components\Dialogs\Tax\EditAjax::init();

        // Init proxy.
        Backend\Notifications\ProxyProviders\Shared::init();
        Backend\Services\ProxyProviders\Local::init();
        Backend\Services\ProxyProviders\Shared::init();
        Backend\Settings\ProxyProviders\Local::init();
        Backend\Settings\ProxyProviders\Shared::init();
        Components\Appearance\ProxyProviders\Shared::init();
        Components\Dialogs\Appointment\AttachPayment\ProxyProviders\Local::init();
        Components\Settings\ProxyProviders\Local::init();
        Frontend\Booking\ProxyProviders\Shared::init();
        Notifications\Assets\Item\ProxyProviders\Shared::init();
        ProxyProviders\Local::init();
        ProxyProviders\Shared::init();
    }
}