<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
?>
<div id="bookly-print-dialog" class="bookly-modal bookly-fade" tabindex=-1 role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e( 'Print', 'bookly' ) ?></h5>
                <button type="button" class="close" data-dismiss="bookly-modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?php $column_num = 0 ?>
                    <?php foreach ( $datatables['settings']['columns'] as $column => $show ) : ?>
                        <?php if ( $show ) : ?>
                            <div class="custom-control custom-checkbox mb-2">
                                <input class="custom-control-input" value="<?php echo $column_num ++ ?>" id="bookly-pa-<?php echo $column_num?>" type="checkbox" checked/>
                                <label class="custom-control-label" for="bookly-pa-<?php echo $column_num?>"><?php echo $datatables['titles'][ $column ] ?></label>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php Buttons::renderSubmit( null, null, __( 'Print', 'bookly' ) ) ?>
            </div>
        </div>
    </div>
</div>