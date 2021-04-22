<?php
namespace BooklyPayson\Lib\Payment\Payson;

/**
 * Class Checkout
 * @package BooklyPayson\Lib\Payment\Payson
 */
class Checkout
{
    /** @var Merchant */
    public $merchant;
    /** @var PayData */
    public $pay_data;
    /** @var Customer */
    public $customer;
    /** @var string */
    public $status;
    /** @var string GUID */
    public $id;
    /** @var int */
    public $purchase_id;
    /** @var string */
    public $snippet;
    /** @var string $description */
    public $description;

    /**
     * Checkout constructor.
     *
     * @param Merchant $merchant
     * @param PayData  $payData
     * @param Customer $customer
     * @param string   $description
     */
    public function __construct( Merchant $merchant, PayData $payData, Customer $customer, $description = '' )
    {
        $this->merchant    = $merchant;
        $this->pay_data    = $payData;
        $this->customer    = $customer;
        $this->purchase_id = null;
        $this->description = $description;
    }

    /**
     * @param \stdClass $data
     * @return Checkout
     */
    public static function create( $data )
    {
        $checkout          = new Checkout( Merchant::create( $data->merchant ), PayData::create( $data->order ), Customer::create( $data->customer ) );
        $checkout->status  = $data->status;
        $checkout->id      = $data->id;
        $checkout->snippet = $data->snippet;
        if ( isset( $data->purchaseId ) ) {
            $checkout->purchase_id = $data->purchaseId;
        }

        if ( isset( $data->description ) ) {
            $checkout->description = $data->description;
        }

        return $checkout;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id'          => $this->id,
            'description' => $this->description,
            'status'      => $this->status,
            'merchant'    => $this->merchant->toArray(),
            'order'       => $this->pay_data->toArray(),
            'gui'         => array(),
            'customer'    => $this->customer->toArray(),
        );
    }
}
