<?php
namespace Bookly2checkout\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Installer
 * @package Bookly2checkout\Lib
 */
class Installer extends Base\Installer
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $status = get_option( 'bookly_pmt_2checkout', '0' );
        $this->options = array(
            'bookly_2checkout_enabled'         => $status == 'disabled' ? '0' : $status,
            'bookly_2checkout_api_secret_word' => get_option( 'bookly_pmt_2checkout_api_secret_word', '' ),
            'bookly_2checkout_api_seller_id'   => get_option( 'bookly_pmt_2checkout_api_seller_id', '' ),
            'bookly_2checkout_sandbox'         => get_option( 'bookly_pmt_2checkout_sandbox', '0' ),
            'bookly_2checkout_increase'        => '0',
            'bookly_2checkout_addition'        => '0',
            'bookly_2checkout_send_tax'        => '0',
            'bookly_l10n_label_pay_2checkout'  => __( 'I will pay now with Credit Card', 'bookly' ),
        );

        $deprecated = array(
            'bookly_pmt_2checkout',
            'bookly_pmt_2checkout_api_secret_word',
            'bookly_pmt_2checkout_api_seller_id',
            'bookly_pmt_2checkout_sandbox',
        );
        foreach ( $deprecated as $option_name ) {
            delete_option( $option_name );
        }
    }
}