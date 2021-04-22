<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Backend\Components\Controls\Inputs;
?>
<div id="bookly-export-coupon-dialog" class="bookly-modal bookly-fade" tabindex=-1 role="dialog">
    <div class="modal-dialog">
        <form action="<?php echo admin_url( 'admin-ajax.php?action=bookly_coupons_export' ) ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php esc_html_e( 'Export to CSV', 'bookly' ) ?></h5>
                    <button type="button" class="close" data-dismiss="bookly-modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="export_customers_delimiter"><?php esc_html_e( 'Delimiter', 'bookly' ) ?></label>
                        <select name="export_customers_delimiter" id="export_customers_delimiter" class="form-control custom-select">
                            <option value=","><?php esc_html_e( 'Comma (,)', 'bookly' ) ?></option>
                            <option value=";"><?php esc_html_e( 'Semicolon (;)', 'bookly' ) ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <?php foreach ( $datatables['coupons']['settings']['columns'] as $column => $show ) : ?>
                            <?php Inputs::renderCheckBox( $datatables['coupons']['titles'][ $column ], null, $show, array( 'name' => 'exp[' . $column . ']' ) ) ?>
                        <?php endforeach ?>
                        <br>
                        <?php Inputs::renderCheckBox( __( 'Export only active coupons', 'bookly' ), 1, true, array( 'name' => 'only_active' ) ) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php Inputs::renderCsrf() ?>
                    <?php Buttons::renderSubmit( null, null, __( 'Export to CSV', 'bookly' ) ) ?>
                </div>
            </div>
        </form>
    </div>
</div>