<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Settings\Inputs;
use Bookly\Backend\Components\Settings\Payments;
use Bookly\Backend\Components\Settings\Selects;
use Bookly\Backend\Components\Controls\Elements;
use BooklyAuthorizeNet\Lib\Plugin;
?>
<div class="card bookly-collapse" data-slug="bookly-addon-authorize-net">
    <div class="card-header d-flex align-items-center">
        <?php Elements::renderReorder() ?>
        <a href="#bookly_pmt_authorize_net" class="ml-2" role="button" data-toggle="collapse">
            Authorize.Net
        </a>
        <img class="ml-auto" src="<?php echo plugins_url( 'frontend/resources/images/authorize_net.png', Plugin::getMainFile() ) ?>"/>
    </div>
    <div id="bookly_pmt_authorize_net" class="collapse show">
        <div class="card-body">
            <?php Selects::renderSingle( 'bookly_authorize_net_enabled', null, null, array( array( '0', __( 'Disabled', 'bookly' ) ), array( 'aim', 'Authorize.Net AIM' ) ) ) ?>
            <div class="bookly-authorize-net">
                <?php Inputs::renderText( 'bookly_authorize_net_api_login_id', __( 'API Login ID', 'bookly' ) ) ?>
                <?php Inputs::renderText( 'bookly_authorize_net_transaction_key', __( 'API Transaction Key', 'bookly' ) ) ?>
                <?php Selects::renderSingle( 'bookly_authorize_net_sandbox', __( 'Sandbox Mode', 'bookly' ), null, array( array( 1, __( 'Yes', 'bookly' ) ), array( 0, __( 'No', 'bookly' ) ) ) ) ?>
                <?php Payments::renderTax( 'authorize_net' ) ?>
                <?php Payments::renderPriceCorrection( 'authorize_net' ) ?>
            </div>
        </div>
    </div>
</div>