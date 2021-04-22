<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe;

/**
 * Class Review
 *
 * @property string $id
 * @property string $object
 * @property string $billing_zip
 * @property string $charge
 * @property string $closed_reason
 * @property int $created
 * @property string $ip_address
 * @property mixed $ip_address_location
 * @property bool $livemode
 * @property bool $open
 * @property string $opened_reason
 * @property string $payment_intent
 * @property string $reason
 * @property mixed $session
 *
 * @package Stripe
 */
class Review extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "review";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;

    /**
     * @param array|string|null $options
     *
     * @return Review The approved review.
     */
    public function approve($params = null, $options = null)
    {
        $url = $this->instanceUrl() . '/approve';
        list($response, $opts) = $this->_request('post', $url, $params, $options);
        $this->refreshFrom($response, $opts);
        return $this;
    }
}
