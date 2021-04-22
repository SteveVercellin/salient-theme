<?php
namespace BooklyPayson\Lib;

use Bookly\Lib as BooklyLib;

/**
 * Class Installer
 * @package BooklyPayson\Lib
 */
class Installer extends Base\Installer
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $status = get_option( 'bookly_pmt_payson', '0' );
        $this->options = array(
            'bookly_payson_enabled'      => $status == 'disabled' ? '0' : $status,
            'bookly_payson_api_agent_id' => get_option( 'bookly_pmt_payson_api_agent_id', '' ),
            'bookly_payson_api_key'      => get_option( 'bookly_pmt_payson_api_key', '' ),
            'bookly_payson_sandbox'      => get_option( 'bookly_pmt_payson_sandbox', '0' ),
            'bookly_payson_timeout'      => '0',
            'bookly_payson_increase'     => '0',
            'bookly_payson_addition'     => '0',
            'bookly_l10n_label_pay_payson' => __( 'I will pay now with Credit Card', 'bookly' ),
        );

        $deprecated = array(
            'bookly_pmt_payson',
            'bookly_pmt_payson_api_agent_id',
            'bookly_pmt_payson_api_key',
            'bookly_pmt_payson_api_receiver_email',
            'bookly_pmt_payson_fees_payer',
            'bookly_pmt_payson_funding',
            'bookly_pmt_payson_sandbox'
        );
        foreach ( $deprecated as $option_name ) {
            delete_option( $option_name );
        }
    }

}