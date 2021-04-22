<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="bookly-gateway-buttons pay-mollie bookly-box bookly-nav-steps" style="display:none">
    <?php BooklyMollie\Lib\Payment\Mollie::renderForm( $form_id, $page_url ) ?>
</div>