<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="bookly-gateway-buttons pay-payu_latam bookly-box bookly-nav-steps" style="display:none">
    <?php BooklyPayuLatam\Lib\Payment\PayuLatam::renderForm( $form_id, $page_url ) ?>
</div>