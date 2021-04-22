<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Settings\Inputs;
use Bookly\Backend\Components\Settings\Payments;
use Bookly\Backend\Components\Settings\Selects;
use Bookly\Backend\Components\Controls\Elements;
use Bookly\Lib\Utils\DateTime;
use BooklyPayuLatam\Lib\Plugin;
?>
<div class="card bookly-collapse" data-slug="bookly-addon-payu-latam">
    <div class="card-header d-flex align-items-center">
        <?php Elements::renderReorder() ?>
        <a href="#bookly_pmt_payu_latam" class="ml-2" role="button" data-toggle="collapse">
            PayU Latam
        </a>
        <img class="ml-auto" src="<?php echo plugins_url( 'frontend/resources/images/payu_latam.png', Plugin::getMainFile() ) ?>" />
    </div>
    <div id="bookly_pmt_payu_latam" class="collapse show">
        <div class="card-body">
            <?php Selects::renderSingle( 'bookly_payu_latam_enabled' ) ?>
            <div class="bookly-payu_latam">
                <?php Selects::renderSingle( 'bookly_payu_latam_sandbox', __( 'Sandbox Mode', 'bookly' ), null, array( array( 0, __( 'No', 'bookly' ) ), array( 1, __( 'Yes', 'bookly' ) ) ) ) ?>
                <?php Inputs::renderText( 'bookly_payu_latam_api_key', __( 'API Key', 'bookly' ) ) ?>
                <?php Inputs::renderText( 'bookly_payu_latam_api_account_id', __( 'Account ID', 'bookly' ) ) ?>
                <?php Inputs::renderText( 'bookly_payu_latam_api_merchant_id', __( 'Merchant ID', 'bookly' ) ) ?>
                <?php Payments::renderTax( 'payu_latam' ) ?>
                <?php Payments::renderPriceCorrection( 'payu_latam' ) ?>
                <?php
                $values = array( array( '0', __( 'OFF', 'bookly' ) ) );
                foreach ( array_merge( range( 1, 23, 1 ), range( 24, 168, 24 ), array( 336, 504, 672 ) ) as $hour ) {
                    $values[] = array( $hour * HOUR_IN_SECONDS, DateTime::secondsToInterval( $hour * HOUR_IN_SECONDS ) );
                }
                Selects::renderSingle( 'bookly_payu_latam_timeout', __( 'Time interval of payment gateway', 'bookly' ), __( 'This setting determines the time limit after which the payment made via the payment gateway is considered to be incomplete. This functionality requires a scheduled cron job.', 'bookly' ), $values );
                ?>
            </div>
        </div>
    </div>
</div>