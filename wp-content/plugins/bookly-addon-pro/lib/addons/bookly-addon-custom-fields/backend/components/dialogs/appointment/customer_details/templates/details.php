<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Dialogs\Appointment\CustomerDetails\Proxy;
?>
<h5 class="text-muted"><?php esc_html_e( 'Custom Fields', 'bookly' ) ?></h5>
<div id="bookly-js-custom-fields">
    <?php foreach ( $custom_fields as $custom_field ) : ?>
        <div class="form-group" data-type="<?php echo esc_attr( $custom_field->type )?>" data-id="<?php echo esc_attr( $custom_field->id ) ?>" data-services="<?php echo esc_attr( json_encode( $custom_field->services ) ) ?>">
            <label for="custom_field_<?php echo esc_attr( $custom_field->id ) ?>"><?php echo $custom_field->label ?></label>
            <div>
                <?php if ( $custom_field->type == 'text-field' ) : ?>
                    <input id="custom_field_<?php echo esc_attr( $custom_field->id ) ?>" type="text" class="bookly-custom-field form-control" />

                <?php elseif ( $custom_field->type == 'textarea' ) : ?>
                    <textarea id="custom_field_<?php echo esc_attr( $custom_field->id ) ?>" rows="3" class="bookly-custom-field form-control"></textarea>

                <?php elseif ( $custom_field->type == 'checkboxes' ) : ?>
                    <?php foreach ( $custom_field->items as $id => $item ) : ?>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input bookly-custom-field" id="bookly-af-cf-<?php echo $custom_field->id . '-' . $id ?>" type=checkbox value="<?php echo esc_attr( $item ) ?>"/>
                            <label class="custom-control-label" for="bookly-af-cf-<?php echo $custom_field->id . '-' . $id ?>"><?php echo $item ?></label>
                        </div>
                    <?php endforeach ?>
                <?php elseif ( $custom_field->type == 'radio-buttons' ) : ?>
                    <?php foreach ( $custom_field->items as $id => $item ) : ?>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input bookly-custom-field" id="bookly-af-cf-<?php echo $custom_field->id . '-' . $id ?>" name="<?php echo $custom_field->id ?>" type=radio value="<?php echo esc_attr( $item ) ?>"/>
                            <label class="custom-control-label" for="bookly-af-cf-<?php echo $custom_field->id . '-' . $id ?>"><?php echo $item ?></label>
                        </div>
                    <?php endforeach ?>
                <?php elseif ( $custom_field->type == 'drop-down' ) : ?>
                    <select id="custom_field_<?php echo esc_attr( $custom_field->id ) ?>" class="bookly-custom-field form-control custom-select">
                        <option value=""></option>
                        <?php foreach ( $custom_field->items as $item ) : ?>
                            <option value="<?php echo esc_attr( $item ) ?>"><?php echo $item ?></option>
                        <?php endforeach ?>
                    </select>
                <?php else : ?>
                    <?php Proxy\Files::renderCustomField( $custom_field ) ?>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
</div>