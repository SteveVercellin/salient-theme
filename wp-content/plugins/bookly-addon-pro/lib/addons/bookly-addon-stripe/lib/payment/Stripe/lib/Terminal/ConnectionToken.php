<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Terminal;

/**
 * Class ConnectionToken
 *
 * @property string $secret
 *
 * @package Stripe\Terminal
 */
class ConnectionToken extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "terminal.connection_token";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
}
