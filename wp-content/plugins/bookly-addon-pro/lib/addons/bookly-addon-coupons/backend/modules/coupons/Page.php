<?php
namespace BooklyCoupons\Backend\Modules\Coupons;

use Bookly\Lib as BooklyLib;

/**
 * Class Page
 * @package BooklyCoupons\Backend\Modules\Coupons
 */
class Page extends BooklyLib\Base\Component
{
    /**
     * Render page.
     */
    public static function render()
    {
        self::enqueueStyles( array(
            'bookly' => array(
                'backend/resources/bootstrap/css/bootstrap.min.css',
                'frontend/resources/css/ladda.min.css',
            ),
        ) );

        self::enqueueScripts( array(
            'bookly' => array(
                'backend/resources/bootstrap/js/bootstrap.min.js' => array( 'jquery' ),
                'backend/resources/js/datatables.min.js'          => array( 'jquery' ),
                'backend/resources/js/select2.min.js'             => array( 'jquery' ),
                'backend/resources/js/moment.min.js'              => array( 'jquery' ),
                'backend/resources/js/daterangepicker.js'         => array( 'bookly-moment.min.js' ),
                'backend/resources/js/dropdown.js'                => array( 'jquery' ),
                'frontend/resources/js/spin.min.js'               => array( 'jquery' ),
                'frontend/resources/js/ladda.min.js'              => array( 'jquery' ),
            ),
            'module' => array( 'js/coupons.js' => array( 'bookly-dropdown.js', 'bookly-daterangepicker.js' ) ),
        ) );

        $services = BooklyLib\Entities\Service::query()
            ->select( 'id, title' )
            ->whereNot( 'type', BooklyLib\Entities\Service::TYPE_PACKAGE )
            ->indexBy( 'id' )
            ->fetchArray();
        $staff_members = BooklyLib\Entities\Staff::query()
            ->select( 'id, full_name AS title' )
            ->indexBy( 'id' )
            ->whereNot( 'visibility', 'archive' )
            ->fetchArray();
        $query = BooklyLib\Entities\Customer::query( 'customer' )
            ->select( 'customer.id, customer.full_name, customer.phone, customer.email' );
        if ( BooklyLib\Entities\Customer::query()->count() < BooklyLib\Entities\Customer::REMOTE_LIMIT ) {
            $remote = false;
        } else {
            /** @var BooklyLib\Entities\Customer $customer */
            $query->innerJoin( 'CouponCustomer', 'cc', 'cc.customer_id = customer.id', '\BooklyCoupons\Lib\Entities' )
                ->leftJoin( 'Coupon', 'c', 'c.id = cc.coupon_id', '\BooklyCoupons\Lib\Entities' )
                ->groupBy( 'customer.id' );
            $remote = true;
        }
        $customers = $query->sortBy( 'full_name' )->indexBy( 'id' )->fetchArray();

        $datatables = BooklyLib\Utils\Tables::getSettings( 'coupons' );

        wp_localize_script( 'bookly-coupons.js', 'BooklyCouponL10n', array(
            'csrfToken'   => BooklyLib\Utils\Common::getCsrfToken(),
            'edit'        => __( 'Edit', 'bookly' ),
            'duplicate'   => __( 'Duplicate', 'bookly' ),
            'zeroRecords' => __( 'No coupons found.', 'bookly' ),
            'processing'  => __( 'Processing...', 'bookly' ),
            'areYouSure'  => __( 'Are you sure?', 'bookly' ),
            'datePicker'  => BooklyLib\Utils\DateTime::datePickerOptions(),
            'noResultFound'  => __( 'No result found', 'bookly' ),
            'searching'      => __( 'Searching', 'bookly' ),
            'removeCustomer' => __( 'Remove customer', 'bookly' ),
            'services' => array(
                'allSelected'     => __( 'All services', 'bookly' ),
                'nothingSelected' => __( 'No service selected', 'bookly' ),
                'collection'      => $services,
            ),
            'staff' => array(
                'allSelected'     => __( 'All staff', 'bookly' ),
                'nothingSelected' => __( 'No staff selected', 'bookly' ),
                'collection'      => $staff_members,
            ),
            'customers' => array(
                'allSelected'     => __( 'All customers', 'bookly' ),
                'nothingSelected' => __( 'No limit', 'bookly' ),
                'collection'      => $customers,
                'remote'          => $remote,
            ),
            'defaultCodeMask'     => get_option( 'bookly_coupons_default_code_mask' ),
            'datatables'          => $datatables,
        ) );

        $dropdown_data = array(
            'service' => BooklyLib\Utils\Common::getServiceDataForDropDown(),
            'staff'   => BooklyLib\Proxy\Pro::getStaffDataForDropDown()
        );
        $customers = array_map( function ( $customer ) {
            unset( $customer['id'] );

            return $customer;
        }, $customers );

        self::renderTemplate( 'index', compact( 'services', 'staff_members', 'customers', 'remote', 'dropdown_data', 'datatables' ) );
    }
}