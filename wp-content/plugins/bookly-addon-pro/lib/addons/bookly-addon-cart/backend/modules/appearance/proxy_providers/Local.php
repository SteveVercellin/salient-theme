<?php
namespace BooklyCart\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;
use BooklyCart\Lib;

/**
 * Class Local
 * @package BooklyCart\Backend\Modules\Appearance\ProxyProviders
 */
class Local extends Proxy\Cart
{
    /**
     * @inheritdoc
     */
    public static function renderCartStepSettings()
    {
        self::renderTemplate( 'cart_step_settings');
    }

    /**
     * @inheritdoc
     */
    public static function renderShowStep()
    {
        self::renderTemplate( 'show_cart_step');
    }

    /**
     * @inheritdoc
     */
    public static function renderStep( $progress_tracker )
    {
        self::renderTemplate( 'cart_step', compact( 'progress_tracker' ) );
    }

    /**
     * @inheritdoc
     */
    public static function renderButton()
    {
        self::renderTemplate( 'button' );
    }
}