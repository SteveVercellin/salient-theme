<?php
namespace BooklyPayson\Lib\Payment\Payson;

/**
 * Class Merchant
 * @package BooklyPayson\Lib\Payment\Payson
 */
class Merchant
{
    /** @var string URI to the merchants checkout page. */
    public $checkout_uri;
    /** @var string URI to the merchants confirmation page. */
    public $confirmation_uri;
    /** @var string Notification URI which receives CPR-status updates. */
    public $notification_uri;
    /** @var string Validation URI which is called to verify an order before it can be paid. */
    public $validation_uri;
    /** @var string URI leading to the sellers terms. */
    public $terms_uri;
    /** @var string Merchants own reference of the checkout. */
    public $reference;
    /** @var string $partner_id Partners unique identifier */
    public $partner_id;
    /** @var string $integration_info Information about the integration. */
    public $integration_info;

    /**
     * Merchant constructor.
     *
     * @param string $checkout_uri
     * @param string $confirmation_uri
     * @param string $notification_uri
     * @param string $terms_uri
     * @param null   $partner_id
     * @param string $integration_info
     */
    public function __construct( $checkout_uri, $confirmation_uri, $notification_uri, $terms_uri, $partner_id = null, $integration_info = 'PaysonCheckout2.0|1.0|NONE' )
    {
        $this->checkout_uri     = $checkout_uri;
        $this->confirmation_uri = $confirmation_uri;
        $this->notification_uri = $notification_uri;
        $this->terms_uri        = $terms_uri;
        $this->partner_id       = $partner_id;
        $this->integration_info = $integration_info;
    }

    /**
     * @param \stdClass $data
     * @return Merchant
     */
    public static function create( $data )
    {
        $merchant                 = new Merchant( $data->checkoutUri, $data->confirmationUri, $data->notificationUri, $data->termsUri, $data->partnerId, $data->integrationInfo );
        $merchant->reference      = $data->reference;
        $merchant->validation_uri = $data->validationUri;

        return $merchant;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'checkoutUri'     => $this->checkout_uri,
            'confirmationUri' => $this->confirmation_uri,
            'notificationUri' => $this->notification_uri,
            'termsUri'        => $this->terms_uri,
            'partnerId'       => $this->partner_id,
            'integrationInfo' => $this->integration_info,
        );
    }
}