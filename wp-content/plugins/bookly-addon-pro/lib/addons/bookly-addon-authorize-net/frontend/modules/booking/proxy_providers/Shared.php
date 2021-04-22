<?php
namespace BooklyAuthorizeNet\Frontend\Modules\Booking\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\Booking\Proxy;
use BooklyAuthorizeNet\Lib;

/**
 * Class Shared
 * @package BooklyAuthorizeNet\Frontend\Modules\Booking\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function preparePaymentOptions( $options, $form_id, $show_price, BooklyLib\CartInfo $cart_info, $payment_status )
    {
        $url_cards_image = plugins_url( 'frontend/resources/images/cards.png', BooklyLib\Plugin::getMainFile() );
        $cart_info->setGateway( BooklyLib\Entities\Payment::TYPE_AUTHORIZENET );

        $options[ Lib\Plugin::getSlug() ] = array(
            'html' => self::renderTemplate(
                'payment_option',
                compact( 'form_id', 'url_cards_image', 'show_price', 'cart_info' ),
                false
            ),
            'pay'  => $cart_info->getPayNow(),
        );

        return $options;
    }
}