<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="form-group">
    <label><?php esc_html_e( 'Taxation', 'bookly' ) ?></label><br/>
    <ul class="bookly-js-simple-dropdown"
        data-container-class="bookly-dropdown-block"
        data-icon-class="fas fa-chart-pie"
        data-txt-select-all="<?php esc_attr_e( 'All', 'bookly' ) ?>"
        data-txt-all-selected="<?php esc_attr_e( 'All', 'bookly' ) ?>"
        data-txt-nothing-selected="<?php esc_attr_e( 'Nothing selected', 'bookly' ) ?>"
    >
        <?php foreach ( $taxes as $tax ) : ?>
            <li
                    data-input-name="taxes[]" data-value="<?php echo $tax['id'] ?>"
                    data-selected="<?php echo (int) in_array( $tax['id'], $service_taxes ) ?>"
            >
                <?php echo esc_html( $tax['title'] ) ?>
            </li>
        <?php endforeach ?>
    </ul>
</div>