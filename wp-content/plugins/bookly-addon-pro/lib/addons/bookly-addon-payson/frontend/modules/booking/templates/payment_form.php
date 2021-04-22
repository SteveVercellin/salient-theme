<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="bookly-gateway-buttons pay-payson bookly-box bookly-nav-steps" style="display:none">
    <?php BooklyPayson\Lib\Payment\Payson::renderForm( $form_id, $page_url ) ?>
</div>