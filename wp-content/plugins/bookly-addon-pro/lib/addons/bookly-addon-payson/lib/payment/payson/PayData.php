<?php
namespace BooklyPayson\Lib\Payment\Payson;

/**
 * Class PayData
 * @package BooklyPayson\Lib\Payment\Payson
 */
class PayData
{
    /** @var string $currency Currency of the order ("sek", "eur"). */
    public $currency;
    /** @var array $items An array with objects of the order items */
    public $items = array();

    /** @var float $totalPriceExcludingTax - Read only */
    public $totalPriceExcludingTax;
    /** @var float $totalPriceIncludingTax - Read only */
    public $totalPriceIncludingTax;
    /** @var float $totalTaxAmount - Read only */
    public $totalTaxAmount;
    /** @var float $totalCreditedAmount - Read only */
    public $totalCreditedAmount;

    public function __construct( $currency )
    {
        $this->currency = $currency;
        $this->items    = array();
    }

    /**
     * @param \stdClass $data
     * @return PayData
     */
    public static function create( $data )
    {
        $pay_data                         = new PayData( $data->currency );
        $pay_data->totalPriceExcludingTax = $data->totalPriceExcludingTax;
        $pay_data->totalPriceIncludingTax = $data->totalPriceIncludingTax;
        $pay_data->totalTaxAmount         = $data->totalTaxAmount;
        $pay_data->totalCreditedAmount    = $data->totalCreditedAmount;

        foreach ( $data->items as $item ) {
            $pay_data->items[] = OrderItem::create( $item );
        }

        return $pay_data;
    }

    /**
     * @param OrderItem $item
     */
    public function addOrderItem( OrderItem $item )
    {
        $this->items[] = $item;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $items = array();
        foreach ( $this->items as $item ) {
            $items[] = $item->toArray();
        }

        return array( 'currency' => $this->currency, 'items' => $items );
    }

}