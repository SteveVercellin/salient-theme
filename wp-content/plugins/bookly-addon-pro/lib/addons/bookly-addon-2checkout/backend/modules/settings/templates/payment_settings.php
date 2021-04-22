<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components;
use Bookly\Backend\Components\Settings\Inputs;
use Bookly\Backend\Components\Settings\Selects;
use Bookly\Backend\Components\Controls\Elements;
use Bookly2checkout\Lib\Plugin;
?>
<div class="card bookly-collapse" data-slug="bookly-addon-2checkout">
    <div class="card-header d-flex align-items-center">
        <?php Elements::renderReorder() ?>
        <a href="#bookly_pmt_2checkout" class="ml-2" role="button" data-toggle="collapse">
            2Checkout
        </a>
        <img class="ml-auto" src="<?php echo plugins_url( 'frontend/resources/images/2Checkout.png', Plugin::getMainFile() ) ?>" />
    </div>
    <div id="bookly_pmt_2checkout" class="collapse show">
        <div class="card-body">
            <?php Selects::renderSingle( 'bookly_2checkout_enabled', null, null, array( array( '0', __( 'Disabled', 'bookly' ) ), array( 'standard_checkout', '2Checkout Standard Checkout' ) ) ) ?>
            <div class="bookly-2checkout">
                <div class="form-group">
                    <h4><?php esc_html_e( 'Instructions', 'bookly' ) ?></h4>
                    <p>
                        <?php _e( 'In <b>Checkout Options</b> of your 2Checkout account do the following steps:', 'bookly' ) ?>
                    </p>
                    <ol>
                        <li><?php _e( 'In <b>Direct Return</b> select <b>Header Redirect (Your URL)</b>.', 'bookly' ) ?></li>
                        <li><?php _e( 'In <b>Approved URL</b> enter the URL of your booking page.', 'bookly' ) ?></li>
                    </ol>
                    <p>
                        <?php esc_html_e( 'Finally provide the necessary information in the form below.', 'bookly' ) ?>
                    </p>
                </div>
                <?php Inputs::renderText( 'bookly_2checkout_api_seller_id', __( 'Account Number', 'bookly' ) ) ?>
                <?php Inputs::renderText( 'bookly_2checkout_api_secret_word', __( 'Secret Word', 'bookly' ) ) ?>
                <?php Selects::renderSingle( 'bookly_2checkout_sandbox', __( 'Sandbox Mode', 'bookly' ), null, array( array( 0, __( 'No', 'bookly' ) ), array( 1, __( 'Yes', 'bookly' ) ) ) ) ?>
                <?php Components\Settings\Payments::renderTax( '2checkout' ) ?>
                <?php Components\Settings\Payments::renderPriceCorrection( '2checkout' ) ?>
            </div>
        </div>
    </div>
</div>