<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Issuing;

/**
 * Class Dispute
 *
 * @property string $id
 * @property string $object
 * @property int $amount
 * @property int $created
 * @property string $currency
 * @property mixed $evidence
 * @property bool $livemode
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\StripeObject $metadata
 * @property string $reason
 * @property string $status
 * @property Transaction $transaction
 *
 * @package Stripe\Issuing
 */
class Dispute extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "issuing.dispute";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Update;
}
