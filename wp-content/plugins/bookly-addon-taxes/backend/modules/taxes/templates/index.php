<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components as BooklyComponents;
use BooklyTaxes\Backend\Components;
/**
 * @var array $datatables
 */
?>
<div id="bookly-tbs" class="wrap">
    <div class="form-row align-items-center mb-3">
        <h4 class="col m-0"><?php esc_html_e( 'Taxes', 'bookly' ) ?></h4>
        <?php BooklyComponents\Support\Buttons::render( $self::pageSlug() ) ?>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="form-row justify-content-end">
                <div class="col-12 col-sm-auto">
                    <?php BooklyComponents\Controls\Buttons::renderAdd( null, 'w-100 mb-3', __( 'Add tax', 'bookly' ), array( 'data-toggle' => 'bookly-modal', 'data-target' => '#bookly-tax-modal' ) ) ?>
                </div>
                <?php BooklyComponents\Dialogs\TableSettings\Dialog::renderButton( 'taxes', 'BooklyTaxesL10n' ) ?>
            </div>

            <table id="bookly-tax" class="table table-striped w-100">
                <thead>
                <tr>
                    <?php foreach ( $datatables['taxes']['settings']['columns'] as $column => $show ) : ?>
                        <?php if ( $show ) : ?>
                            <th><?php echo $datatables['taxes']['titles'][ $column ] ?></th>
                        <?php endif ?>
                    <?php endforeach ?>
                    <th width="75"></th>
                    <th width="16"><?php BooklyComponents\Controls\Inputs::renderCheckBox( null, null, null, array( 'id' => 'bookly-check-all' ) ) ?></th>
                </tr>
                </thead>
            </table>

            <div class="text-right mt-3">
                <?php BooklyComponents\Controls\Buttons::renderDelete() ?>
            </div>
        </div>
    </div>

    <?php Components\Dialogs\Tax\Edit::render() ?>
    <?php BooklyComponents\Dialogs\TableSettings\Dialog::render() ?>
</div>