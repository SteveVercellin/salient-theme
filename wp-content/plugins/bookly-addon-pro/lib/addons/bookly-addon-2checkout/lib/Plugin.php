<?php
namespace Bookly2checkout\Lib;

use Bookly\Lib as BooklyLib;
use Bookly2checkout\Backend\Modules as Backend;
use Bookly2checkout\Frontend\Modules as Frontend;

/**
 * Class Plugin
 * @package Bookly2checkout\Lib
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
        // Init proxy.
        Backend\Appearance\ProxyProviders\Shared::init();
        Backend\Payments\ProxyProviders\Shared::init();
        Backend\Settings\ProxyProviders\Shared::init();

        if ( get_option( 'bookly_2checkout_enabled' ) ) {
            Frontend\Booking\ProxyProviders\Shared::init();
        }
        ProxyProviders\Shared::init();
    }
}