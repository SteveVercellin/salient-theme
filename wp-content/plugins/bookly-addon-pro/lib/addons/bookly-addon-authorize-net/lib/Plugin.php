<?php
namespace BooklyAuthorizeNet\Lib;

use Bookly\Lib as BooklyLib;
use BooklyAuthorizeNet\Backend\Modules as Backend;
use BooklyAuthorizeNet\Frontend\Modules as Frontend;

/**
 * Class Plugin
 * @package BooklyAuthorizeNet\Lib
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
        if ( get_option( 'bookly_authorize_net_enabled' ) ) {
            Frontend\AuthorizeNet\Ajax::init();
        }

        // Init proxy.
        Backend\Appearance\ProxyProviders\Shared::init();
        Backend\Payments\ProxyProviders\Shared::init();
        Backend\Settings\ProxyProviders\Shared::init();
        if ( get_option( 'bookly_authorize_net_enabled' ) ) {
            Frontend\Booking\ProxyProviders\Shared::init();
        }
        ProxyProviders\Shared::init();
    }
}