<?php
namespace BooklyPayson\Lib;

/**
 * Class Updates
 * @package BooklyPayson\Lib
 */
class Updater extends \Bookly\Lib\Base\Updater
{
    public function update_2_2()
    {
        $this->addL10nOptions( array( 'bookly_l10n_label_pay_payson' => __( 'I will pay now with Credit Card', 'bookly' ) ) );
    }

    public function update_1_2()
    {
        delete_option( 'bookly_payson_api_receiver_email' );
        delete_option( 'bookly_payson_fees_payer' );
        delete_option( 'bookly_payson_funding' );
    }

    public function update_1_1()
    {
        add_option( 'bookly_payson_increase', '0' );
        add_option( 'bookly_payson_addition', '0' );
        add_option( 'bookly_payson_timeout', '0' );
    }
}