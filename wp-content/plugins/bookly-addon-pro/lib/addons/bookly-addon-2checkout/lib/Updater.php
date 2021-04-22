<?php
namespace Bookly2checkout\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Updates
 * @package Bookly2checkout\Lib
 */
class Updater extends BooklyLib\Base\Updater
{
    public function update_1_9()
    {
        $this->addL10nOptions( array( 'bookly_l10n_label_pay_2checkout' => __( 'I will pay now with Credit Card', 'bookly' ) ) );
    }

    public function update_1_2()
    {
        add_option( 'bookly_2checkout_send_tax', '0' );
    }

    public function update_1_1()
    {
        add_option( 'bookly_2checkout_increase', '0' );
        add_option( 'bookly_2checkout_addition', '0' );
    }
}