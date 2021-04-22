<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Controls;
use Bookly\Backend\Components\Support;
use Bookly\Backend\Components\Dialogs;

/** @var array $datatables */
?>
<div id="bookly-tbs" class="wrap">
    <div class="form-row align-items-center mb-3">
        <h4 class="col m-0"><?php esc_html_e( 'Coupons', 'bookly' ) ?></h4>
        <?php Support\Buttons::render( $self::pageSlug() ) ?>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="form-row justify-content-end">
                <div class="col-12 col-sm-auto">
                    <?php Controls\Buttons::render( null, 'btn-default w-100 mb-3', __( 'Export to CSV', 'bookly' ), array( 'data-toggle' => 'bookly-modal', 'data-target' => '#bookly-export-coupon-dialog' ), '<i class="far fa-fw fa-share-square mr-1"></i>{caption}…' ) ?>
                </div>
                <div class="col-12 col-sm-auto">
                    <?php Controls\Buttons::renderAdd( 'bookly-add-series', 'w-100 mb-3', __( 'Add Coupon Series', 'bookly' ) ) ?>
                </div>
                <div class="col-12 col-sm-auto">
                    <?php Controls\Buttons::renderAdd( 'bookly-add', 'w-100 mb-3', __( 'Add Coupon', 'bookly' ) ) ?>
                </div>
                <?php Dialogs\TableSettings\Dialog::renderButton( 'coupons', 'BooklyCouponL10n' ) ?>
            </div>
            <div class="form-row align-items-center">
                <div class="col-md-4">
                    <div class="form-group">
                        <input class="form-control" type="text" id="bookly-filter-code" placeholder="<?php esc_attr_e( 'Coupon code', 'bookly' ) ?>"/>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="form-group">
                        <select class="form-control bookly-js-select" id="bookly-filter-service" data-placeholder="<?php esc_attr_e( 'Service', 'bookly' ) ?>">
                            <?php foreach ( $services as $service ): ?>
                                <option value="<?php echo $service['id'] ?>"><?php echo esc_html( $service['title'] ) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="form-group">
                        <select class="form-control bookly-js-select" id="bookly-filter-staff" data-placeholder="<?php esc_attr_e( 'Staff', 'bookly' ) ?>">
                            <?php foreach ( $staff_members as $staff ): ?>
                                <option value="<?php echo $staff['id'] ?>"><?php echo esc_html( $staff['title'] ) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="form-group">
                        <select class="form-control <?php echo $remote ? 'bookly-js-select-ajax' : 'bookly-js-select' ?>" id="bookly-filter-customer"
                                data-placeholder="<?php esc_attr_e( 'Customer', 'bookly' ) ?>" <?php echo $remote ? 'data-ajax--action' : 'data-action' ?>="bookly_get_customers_list">
                        <?php foreach ( $customers as $customer_id => $customer ) : ?>
                            <option value="<?php echo $customer_id ?>" data-search='<?php echo json_encode( array_values( $customer ) ) ?>'><?php echo esc_html( $customer['full_name'] ) ?></option>
                        <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="form-group">
                        <?php Controls\Inputs::renderCheckBox( __( 'Show only active', 'bookly' ), null, null, array( 'id' => 'bookly-filter-active' ) ) ?>
                    </div>
                </div>
            </div>
            <table id="bookly-coupons-list" class="table table-striped w-100">
                <thead>
                <tr>
                    <?php foreach ( $datatables['coupons']['settings']['columns'] as $column => $show ) : ?>
                        <?php if ( $show ) : ?>
                            <th><?php echo $datatables['coupons']['titles'][ $column ] ?></th>
                        <?php endif ?>
                    <?php endforeach ?>
                    <th width="200"></th>
                    <th width="16"><?php Controls\Inputs::renderCheckBox( null, null, null, array( 'id' => 'bookly-check-all' ) ) ?></th>
                </tr>
                </thead>
            </table>

            <div class="text-right mt-3">
                <?php Controls\Buttons::renderDelete() ?>
            </div>
        </div>
    </div>
    <?php $self::renderTemplate( 'coupon', compact( 'services', 'dropdown_data', 'customers' ) ) ?>
    <?php $self::renderTemplate( 'export', compact( 'datatables' ) ) ?>
    <?php Dialogs\TableSettings\Dialog::render() ?>
</div>