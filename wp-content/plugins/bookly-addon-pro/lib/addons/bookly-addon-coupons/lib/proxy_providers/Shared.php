<?php
namespace BooklyCoupons\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;

/**
 * Class Shared
 * @package BooklyCoupons\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function preparePaymentDetails( $details, BooklyLib\DataHolders\Booking\Order $order, BooklyLib\CartInfo $cart_info )
    {
        $coupon = $cart_info->getCoupon();
        if ( $coupon ) {
            $details['coupon'] = array(
                'code'      => $coupon->getCode(),
                'discount'  => $coupon->getDiscount(),
                'deduction' => $coupon->getDeduction(),
            );
        }

        return $details;
    }

    /**
     * @inheritDoc
     */
    public static function prepareTableColumns( $columns, $table )
    {
        if ( $table == BooklyLib\Utils\Tables::COUPONS ) {
            $columns = array_merge( $columns, array(
                'code'             => esc_html__( 'Code', 'bookly' ),
                'discount'         => esc_html__( 'Discount (%)', 'bookly' ),
                'deduction'        => esc_html__( 'Deduction', 'bookly' ),
                'services'         => esc_html__( 'Services', 'bookly' ),
                'staff'            => esc_html__( 'Staff', 'bookly' ),
                'customers'        => esc_html__( 'Customers limit', 'bookly' ),
                'usage_limit'      => esc_html__( 'Usage limit', 'bookly' ),
                'used'             => esc_html__( 'Number of times used', 'bookly' ),
                'date_limit_start' => esc_html__( 'Active from', 'bookly' ),
                'date_limit_end'   => esc_html__( 'Active until', 'bookly' ),
                'min_appointments' => esc_html__( 'Min. appointments', 'bookly' ),
                'max_appointments' => esc_html__( 'Max. appointments', 'bookly' ),
            ) );
        }

        return $columns;
    }
}