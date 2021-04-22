<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Backend\Components\Controls\Inputs;
?>
<div class="bookly-modal bookly-fade" id="bookly-tax-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="bookly-tax-modal-title"></h5>
                    <button type="button" class="close" data-dismiss="bookly-modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class=form-group>
                        <label for="bookly-tax-title"><?php esc_html_e( 'Title', 'bookly' ) ?></label>
                        <input type="text" id="bookly-tax-title" class="form-control" name="title" required/>
                    </div>
                    <div class=form-group>
                        <label for="bookly-tax-rate"><?php esc_html_e( 'Rate', 'bookly' ) ?></label>
                        <input type="number" id="bookly-tax-rate" class="form-control" name="rate" step="any" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php Inputs::renderCsrf() ?>
                    <?php Buttons::renderSubmit( 'bookly-tax-save' ) ?>
                    <?php Buttons::renderCancel() ?>
                </div>
            </form>
        </div>
    </div>
</div>