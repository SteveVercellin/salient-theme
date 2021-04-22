<?php
namespace BooklyAuthorizeNet\Lib;

/**
 * Class Updates
 * @package BooklyAuthorizeNet\Lib
 */
class Updater extends \Bookly\Lib\Base\Updater
{
    public function update_1_9()
    {
        $this->addL10nOptions( array( 'bookly_l10n_label_pay_authorize_net' => __( 'I will pay now with Credit Card', 'bookly' ) ) );
    }

    public function update_1_2()
    {
        add_option( 'bookly_authorize_net_send_tax', '0' );
    }

    public function update_1_1()
    {
        add_option( 'bookly_authorize_net_increase', '0' );
        add_option( 'bookly_authorize_net_addition', '0' );
    }
}