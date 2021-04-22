<?php
namespace BooklyCart\Lib;

use Bookly\Lib;
use BooklyCart\Backend\Modules as Backend;
use BooklyCart\Frontend\Modules as Frontend;

/**
 * Class Plugin
 * @package BooklyCart\Lib
 */
abstract class Plugin extends Lib\Base\Plugin
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
        // Init proxy.
        Backend\Appearance\ProxyProviders\Local::init();
        Backend\Appearance\ProxyProviders\Shared::init();
        if ( get_option( 'bookly_cart_enabled' ) ) {
            Frontend\Booking\ProxyProviders\Local::init();
            Frontend\Booking\Ajax::init();
        }
    }
}