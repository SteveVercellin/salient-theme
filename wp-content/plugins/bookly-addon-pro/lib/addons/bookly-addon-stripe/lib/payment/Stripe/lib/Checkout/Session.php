<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Checkout;

/**
 * Class Session
 *
 * @property string $id
 * @property string $object
 * @property bool $livemode
 *
 * @package Stripe
 */
class Session extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{

    const OBJECT_NAME = "checkout.session";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
}
