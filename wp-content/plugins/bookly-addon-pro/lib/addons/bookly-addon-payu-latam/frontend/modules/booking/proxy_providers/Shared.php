<?php
namespace BooklyPayuLatam\Frontend\Modules\Booking\ProxyProviders;

use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\Booking\Proxy;
use BooklyPayuLatam\Lib;

/**
 * Class Shared
 * @package BooklyPayuLatam\Frontend\Modules\Booking\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function preparePaymentOptions( $options, $form_id, $show_price, BooklyLib\CartInfo $cart_info, $payment_status )
    {
        $url_cards_image = plugins_url( 'frontend/resources/images/cards.png', BooklyLib\Plugin::getMainFile() );
        $cart_info->setGateway( BooklyLib\Entities\Payment::TYPE_PAYULATAM );
        $options[ Lib\Plugin::getSlug() ] = array(
            'html' => self::renderTemplate(
                'payment_option',
                compact( 'form_id', 'url_cards_image', 'show_price', 'cart_info', 'payment_status' ),
                false
            ),
            'pay'  => $cart_info->getPayNow(),
        );

        return $options;
    }

    /**
     * @inheritdoc
     */
    public static function renderPaymentForms( $form_id, $page_url )
    {
        self::renderTemplate( 'payment_form', compact( 'form_id', 'page_url' ) );
    }
}