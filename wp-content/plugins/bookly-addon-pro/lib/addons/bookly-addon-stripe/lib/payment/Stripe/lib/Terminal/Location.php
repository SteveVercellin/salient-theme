<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Terminal;

/**
 * Class Location
 *
 * @property string $id
 * @property string $object
 * @property string $display_name
 * @property string $address_city
 * @property string $address_country
 * @property string $address_line1
 * @property string $address_line2
 * @property string $address_state
 * @property string $address_postal_code
 *
 * @package Stripe\Terminal
 */
class Location extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "terminal.location";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Update;
}
