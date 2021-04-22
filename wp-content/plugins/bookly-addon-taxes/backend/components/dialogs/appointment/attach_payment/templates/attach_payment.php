<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="form-group">
    <label for="bookly-attach-payment-tax"><?php esc_html_e( 'IVA', 'bookly' ) ?></label>
    <input id="bookly-attach-payment-tax" class="form-control bookly-js-attach-payment-tax" type="text" ng-model="form.attach.payment_tax" <?php if ( get_option( 'bookly_taxes_in_price' ) == 'included' ) : ?> ng-class="{'is-invalid': (form.attach.payment_price * 1) < (form.attach.payment_tax * 1)}"<?php endif ?>/>
</div>

