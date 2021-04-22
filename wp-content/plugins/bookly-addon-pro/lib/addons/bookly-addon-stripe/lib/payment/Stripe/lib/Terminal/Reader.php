<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Terminal;

/**
 * Class Reader
 *
 * @property string $id
 * @property string $object
 * @property string $device_type
 * @property string $serial_number
 * @property string $label
 * @property string $ip_address
 *
 * @package Stripe\Terminal
 */
class Reader extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "terminal.reader";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Update;
}
