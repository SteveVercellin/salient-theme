<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Radar;

/**
 * Class ValueListItem
 *
 * @property string $id
 * @property string $object
 * @property int $created
 * @property string $created_by
 * @property string $list
 * @property bool $livemode
 * @property string $value
 *
 * @package Stripe\Radar
 */
class ValueListItem extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "radar.value_list_item";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Delete;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
}
