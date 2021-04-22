<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="bookly-gateway-buttons pay-2checkout bookly-box bookly-nav-steps" style="display:none">
    <?php Bookly2checkout\Lib\Payment\TwoCheckout::renderForm( $form_id, $page_url ) ?>
</div>