<?php
namespace BooklyMollie\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Installer
 * @package BooklyMollie\Lib
 */
class Installer extends Base\Installer
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $status = get_option( 'bookly_pmt_mollie', '0' );
        $this->options = array(
            'bookly_mollie_enabled'        => $status == 'disabled' ? '0' : $status,
            'bookly_mollie_api_key'        => get_option( 'bookly_pmt_mollie_api_key', '' ),
            'bookly_l10n_label_pay_mollie' => __( 'I will pay now with Mollie', 'bookly' ),
            'bookly_mollie_timeout'        => '0',
            'bookly_mollie_increase'       => '0',
            'bookly_mollie_addition'       => '0',
        );

        $deprecated = array(
            'bookly_pmt_mollie',
            'bookly_pmt_mollie_api_key',
        );
        foreach ( $deprecated as $option_name ) {
            delete_option( $option_name );
        }
    }

}