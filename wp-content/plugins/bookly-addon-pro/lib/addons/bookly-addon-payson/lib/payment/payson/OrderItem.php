<?php
namespace BooklyPayson\Lib\Payment\Payson;

/**
 * Class OrderItem
 * @package BooklyPayson\Lib\Payment\Payson
 */
class OrderItem
{
    /** @var string $id */
    public $item_id;
    /** @var float $discount_rate Discount rate of the article (Decimal number (0.00-1.00)). */
    public $discount_rate;
    /** @var float $credited_amount Credited amount (Decimal number (with two decimals)). */
    public $credited_amount;
    /** @var string $image_uri An URI to an image of the article. */
    public $image_uri;
    /** @var string $name Name of the article. */
    public $name;
    /** @var float $unit_price Unit price of the article (including tax). */
    public $unit_price;
    /** @var int $quantity Quantity of the article. */
    public $quantity;
    /** @var float tax_rate Tax rate of the article (0.00-1.00). */
    public $tax_rate;
    /** @var string $reference Article reference, usually the article number. */
    public $reference;
    /** @var string $type Type of article ("Fee", "Physical" (default), "Service"). */
    public $type = 'service';
    /** @var string $uri URI to a the article page of the order item. */
    public $uri;
    /** @var string $ean European Article Number. Discrete number (13 digits) */
    public $ean;

    /**
     * Constructs an OrderItem object
     * If any other value than description is provided all of them has to be entered
     *
     * @param string $name       Name of order item. Max 128 characters
     * @param float  $unit_price Unit price incl. VAT
     * @param int    $quantity   Quantity of this item. Can have at most 2 decimal places
     * @param float  $tax_rate   Tax value. Not actual percentage. For example, 25% has to be entered as 0.25
     * @param string $reference  Sku of item
     */
    public function __construct( $name, $unit_price, $quantity, $tax_rate, $reference )
    {
        $this->name       = $name;
        $this->unit_price = $unit_price;
        $this->quantity   = $quantity;
        $this->tax_rate   = $tax_rate;
        $this->reference  = $reference;
    }

    /**
     * @param \stdClass $data
     * @return OrderItem
     */
    public static function create( $data )
    {
        $item = new OrderItem( $data->name, $data->unitPrice, $data->quantity, $data->taxRate, $data->reference );
        $item->discount_rate   = $data->discountRate;
        $item->credited_amount = $data->creditedAmount;
        if ( isset( $data->itemId ) ) {
            $item->item_id = $data->itemId;
        }

        return $item;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'discountRate'   => $this->discount_rate,
            'creditedAmount' => $this->credited_amount,
            'ean'            => $this->ean,
            'imageUri'       => $this->image_uri,
            'itemId'         => $this->item_id,
            'name'           => $this->name,
            'quantity'       => $this->quantity,
            'reference'      => $this->reference,
            'taxRate'        => $this->tax_rate,
            'type'           => $this->type,
            'unitPrice'      => $this->unit_price,
            'totalPriceIncludingTax' => $this->unit_price,
            'uri'            => $this->uri,
        );
    }
}
