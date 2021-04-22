<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Issuing;

/**
 * Class Transaction
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property string $authorization
 * @property string $balance_transaction
 * @property string $card
 * @property string $cardholder
 * @property int $created
 * @property string $currency
 * @property string $dispute
 * @property bool $livemode
 * @property mixed $merchant_data
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\StripeObject $metadata
 * @property string $type
 *
 * @package Stripe\Issuing
 */
class Transaction extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "issuing.transaction";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Update;
}
