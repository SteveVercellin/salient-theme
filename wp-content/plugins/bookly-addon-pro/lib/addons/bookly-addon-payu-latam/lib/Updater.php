<?php
namespace BooklyPayuLatam\Lib;

/**
 * Class Updates
 * @package BooklyPayuLatam\Lib
 */
class Updater extends \Bookly\Lib\Base\Updater
{
    public function update_2_2()
    {
        $this->addL10nOptions( array( 'bookly_l10n_label_pay_payu_latam' => __( 'I will pay now with Credit Card', 'bookly' ) ) );
    }

    public function update_1_4()
    {
        add_option( 'bookly_payu_latam_send_tax', '0' );
    }

    public function update_1_3()
    {
        add_option( 'bookly_payu_latam_increase', '0' );
        add_option( 'bookly_payu_latam_addition', '0' );
        add_option( 'bookly_payu_latam_timeout', '0' );
    }

    public function update_1_2()
    {
        $status = get_option( 'bookly_pmt_payu_latam' );
        delete_option( 'bookly_pmt_payu_latam' );
        add_option( 'bookly_payu_latam_enabled', $status == 'disabled' ? '0' : $status );
    }
}