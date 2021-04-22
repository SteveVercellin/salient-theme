<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Reporting;

/**
 * Class ReportType
 *
 * @property string $id
 * @property string $object
 * @property int $data_available_end
 * @property int $data_available_start
 * @property string $name
 * @property int $updated
 * @property string $version
 *
 * @package Stripe\Reporting
 */
class ReportType extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = "reporting.report_type";

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
}
