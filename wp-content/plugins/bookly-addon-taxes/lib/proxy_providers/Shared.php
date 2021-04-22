<?php
namespace BooklyTaxes\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;

/**
 * Class Shared
 * @package BooklyTaxes\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function preparePaymentDetails( $details, BooklyLib\DataHolders\Booking\Order $order, BooklyLib\CartInfo $cart_info )
    {
        $payment = $order->getPayment();
        if ( $payment && ! in_array( $payment->getType(), array( BooklyLib\Entities\Payment::TYPE_LOCAL, BooklyLib\Entities\Payment::TYPE_FREE, BooklyLib\Entities\Payment::TYPE_WOOCOMMERCE ) ) ) {
            $details['tax_paid'] = (string) $cart_info->getPayTax();
        } else {
            $details['tax_paid'] = null;
        }
        $details['tax_in_price'] = get_option( 'bookly_taxes_in_price' );

        return $details;
    }

    /**
     * @inheritDoc
     */
    public static function prepareTableColumns( $columns, $table )
    {
        if ( $table == BooklyLib\Utils\Tables::TAXES ) {
            $columns = array_merge( $columns, array(
                'title' => esc_html__( 'Title', 'bookly' ),
                'rate'  => esc_html__( 'Rate', 'bookly' ),
            ) );
        }

        return $columns;
    }
}