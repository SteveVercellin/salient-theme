<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Util;

use BooklyStripe\Lib\Payment\Lib\Stripe\StripeObject;

abstract class Util
{
    private static $isMbstringAvailable = null;
    private static $isHashEqualsAvailable = null;

    /**
     * Whether the provided array (or other) is a list rather than a dictionary.
     * A list is defined as an array for which all the keys are consecutive
     * integers starting at 0. Empty arrays are considered to be lists.
     *
     * @param array|mixed $array
     * @return boolean true if the given object is a list.
     */
    public static function isList($array)
    {
        if (!is_array($array)) {
            return false;
        }
        if ($array === []) {
            return true;
        }
        if (array_keys($array) !== range(0, count($array) - 1)) {
            return false;
        }
        return true;
    }

    /**
     * Recursively converts the PHP Stripe object to an array.
     *
     * @param array $values The PHP Stripe object to convert.
     * @return array
     */
    public static function convertStripeObjectToArray($values)
    {
        $results = [];
        foreach ($values as $k => $v) {
            // FIXME: this is an encapsulation violation
            if ($k[0] == '_') {
                continue;
            }
            if ($v instanceof StripeObject) {
                $results[$k] = $v->__toArray(true);
            } elseif (is_array($v)) {
                $results[$k] = self::convertStripeObjectToArray($v);
            } else {
                $results[$k] = $v;
            }
        }
        return $results;
    }

    /**
     * Converts a response from the Stripe API to the corresponding PHP object.
     *
     * @param array $resp The response from the Stripe API.
     * @param array $opts
     * @return StripeObject|array
     */
    public static function convertToStripeObject($resp, $opts)
    {
        $types = [
            // data structures
            \BooklyStripe\Lib\Payment\Lib\Stripe\Collection::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Collection',

            // business objects
            \BooklyStripe\Lib\Payment\Lib\Stripe\Account::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Account',
            \BooklyStripe\Lib\Payment\Lib\Stripe\AccountLink::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\AccountLink',
            \BooklyStripe\Lib\Payment\Lib\Stripe\AlipayAccount::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\AlipayAccount',
            \BooklyStripe\Lib\Payment\Lib\Stripe\ApplePayDomain::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\ApplePayDomain',
            \BooklyStripe\Lib\Payment\Lib\Stripe\ApplicationFee::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\ApplicationFee',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Balance::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Balance',
            \BooklyStripe\Lib\Payment\Lib\Stripe\BalanceTransaction::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\BalanceTransaction',
            \BooklyStripe\Lib\Payment\Lib\Stripe\BankAccount::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\BankAccount',
            \BooklyStripe\Lib\Payment\Lib\Stripe\BitcoinReceiver::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\BitcoinReceiver',
            \BooklyStripe\Lib\Payment\Lib\Stripe\BitcoinTransaction::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\BitcoinTransaction',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Card::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Card',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Charge::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Charge',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Checkout\Session::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Checkout\\Session',
            \BooklyStripe\Lib\Payment\Lib\Stripe\CountrySpec::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\CountrySpec',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Coupon::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Coupon',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Customer::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Customer',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Discount::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Discount',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Dispute::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Dispute',
            \BooklyStripe\Lib\Payment\Lib\Stripe\EphemeralKey::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\EphemeralKey',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Event::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Event',
            \BooklyStripe\Lib\Payment\Lib\Stripe\ExchangeRate::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\ExchangeRate',
            \BooklyStripe\Lib\Payment\Lib\Stripe\ApplicationFeeRefund::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\ApplicationFeeRefund',
            \BooklyStripe\Lib\Payment\Lib\Stripe\File::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\File',
            \BooklyStripe\Lib\Payment\Lib\Stripe\File::OBJECT_NAME_ALT => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\File',
            \BooklyStripe\Lib\Payment\Lib\Stripe\FileLink::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\FileLink',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Invoice::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Invoice',
            \BooklyStripe\Lib\Payment\Lib\Stripe\InvoiceItem::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\InvoiceItem',
            \BooklyStripe\Lib\Payment\Lib\Stripe\InvoiceLineItem::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\InvoiceLineItem',
            \BooklyStripe\Lib\Payment\Lib\Stripe\IssuerFraudRecord::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\IssuerFraudRecord',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Authorization::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Issuing\\Authorization',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Card::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Issuing\\Card',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\CardDetails::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Issuing\\CardDetails',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Cardholder::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Issuing\\Cardholder',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Dispute::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Issuing\\Dispute',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Transaction::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Issuing\\Transaction',
            \BooklyStripe\Lib\Payment\Lib\Stripe\LoginLink::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\LoginLink',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Order::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Order',
            \BooklyStripe\Lib\Payment\Lib\Stripe\OrderItem::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\OrderItem',
            \BooklyStripe\Lib\Payment\Lib\Stripe\OrderReturn::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\OrderReturn',
            \BooklyStripe\Lib\Payment\Lib\Stripe\PaymentIntent::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\PaymentIntent',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Payout::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Payout',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Person::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Person',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Plan::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Plan',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Product::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Product',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\ValueList::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Radar\\ValueList',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\ValueListItem::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Radar\\ValueListItem',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Recipient::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Recipient',
            \BooklyStripe\Lib\Payment\Lib\Stripe\RecipientTransfer::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\RecipientTransfer',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Refund::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Refund',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Reporting\ReportRun::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Reporting\\ReportRun',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Reporting\ReportType::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Reporting\\ReportType',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Review::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Review',
            \BooklyStripe\Lib\Payment\Lib\Stripe\SKU::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\SKU',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Sigma\ScheduledQueryRun::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Sigma\\ScheduledQueryRun',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Source::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Source',
            \BooklyStripe\Lib\Payment\Lib\Stripe\SourceTransaction::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\SourceTransaction',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Subscription::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Subscription',
            \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionItem::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\SubscriptionItem',
            \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionSchedule::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\SubscriptionSchedule',
            \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionScheduleRevision::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\SubscriptionScheduleRevision',
            \BooklyStripe\Lib\Payment\Lib\Stripe\ThreeDSecure::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\ThreeDSecure',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\ConnectionToken::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Terminal\\ConnectionToken',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\Location::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Terminal\\Location',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\Reader::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Terminal\\Reader',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Token::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Token',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Topup::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Topup',
            \BooklyStripe\Lib\Payment\Lib\Stripe\Transfer::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\Transfer',
            \BooklyStripe\Lib\Payment\Lib\Stripe\TransferReversal::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\TransferReversal',
            \BooklyStripe\Lib\Payment\Lib\Stripe\UsageRecord::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\UsageRecord',
            \BooklyStripe\Lib\Payment\Lib\Stripe\UsageRecordSummary::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\UsageRecordSummary',
            \BooklyStripe\Lib\Payment\Lib\Stripe\WebhookEndpoint::OBJECT_NAME => 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\WebhookEndpoint',
        ];
        if (self::isList($resp)) {
            $mapped = [];
            foreach ($resp as $i) {
                array_push($mapped, self::convertToStripeObject($i, $opts));
            }
            return $mapped;
        } elseif (is_array($resp)) {
            if (isset($resp['object']) && is_string($resp['object']) && isset($types[$resp['object']])) {
                $class = $types[$resp['object']];
            } else {
                $class = 'BooklyStripe\\Lib\\Payment\\Lib\\Stripe\\StripeObject';
            }
            return $class::constructFrom($resp, $opts);
        } else {
            return $resp;
        }
    }

    /**
     * @param string|mixed $value A string to UTF8-encode.
     *
     * @return string|mixed The UTF8-encoded string, or the object passed in if
     *    it wasn't a string.
     */
    public static function utf8($value)
    {
        if (self::$isMbstringAvailable === null) {
            self::$isMbstringAvailable = function_exists('mb_detect_encoding');

            if (!self::$isMbstringAvailable) {
                trigger_error("It looks like the mbstring extension is not enabled. " .
                    "UTF-8 strings will not properly be encoded. Ask your system " .
                    "administrator to enable the mbstring extension, or write to " .
                    "support@stripe.com if you have any questions.", E_USER_WARNING);
            }
        }

        if (is_string($value) && self::$isMbstringAvailable && mb_detect_encoding($value, "UTF-8", true) != "UTF-8") {
            return utf8_encode($value);
        } else {
            return $value;
        }
    }

    /**
     * Compares two strings for equality. The time taken is independent of the
     * number of characters that match.
     *
     * @param string $a one of the strings to compare.
     * @param string $b the other string to compare.
     * @return bool true if the strings are equal, false otherwise.
     */
    public static function secureCompare($a, $b)
    {
        if (self::$isHashEqualsAvailable === null) {
            self::$isHashEqualsAvailable = function_exists('hash_equals');
        }

        if (self::$isHashEqualsAvailable) {
            return hash_equals($a, $b);
        } else {
            if (strlen($a) != strlen($b)) {
                return false;
            }

            $result = 0;
            for ($i = 0; $i < strlen($a); $i++) {
                $result |= ord($a[$i]) ^ ord($b[$i]);
            }
            return ($result == 0);
        }
    }

    /**
     * Recursively goes through an array of parameters. If a parameter is an instance of
     * ApiResource, then it is replaced by the resource's ID.
     * Also clears out null values.
     *
     * @param mixed $h
     * @return mixed
     */
    public static function objectsToIds($h)
    {
        if ($h instanceof \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource) {
            return $h->id;
        } elseif (static::isList($h)) {
            $results = [];
            foreach ($h as $v) {
                array_push($results, static::objectsToIds($v));
            }
            return $results;
        } elseif (is_array($h)) {
            $results = [];
            foreach ($h as $k => $v) {
                if (is_null($v)) {
                    continue;
                }
                $results[$k] = static::objectsToIds($v);
            }
            return $results;
        } else {
            return $h;
        }
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function encodeParameters($params)
    {
        $flattenedParams = self::flattenParams($params);
        $pieces = [];
        foreach ($flattenedParams as $param) {
            list($k, $v) = $param;
            array_push($pieces, self::urlEncode($k) . '=' . self::urlEncode($v));
        }
        return implode('&', $pieces);
    }

    /**
     * @param array $params
     * @param string|null $parentKey
     *
     * @return array
     */
    public static function flattenParams($params, $parentKey = null)
    {
        $result = [];

        foreach ($params as $key => $value) {
            $calculatedKey = $parentKey ? "{$parentKey}[{$key}]" : $key;

            if (self::isList($value)) {
                $result = array_merge($result, self::flattenParamsList($value, $calculatedKey));
            } elseif (is_array($value)) {
                $result = array_merge($result, self::flattenParams($value, $calculatedKey));
            } else {
                array_push($result, [$calculatedKey, $value]);
            }
        }

        return $result;
    }

    /**
     * @param array $value
     * @param string $calculatedKey
     *
     * @return array
     */
    public static function flattenParamsList($value, $calculatedKey)
    {
        $result = [];

        foreach ($value as $i => $elem) {
            if (self::isList($elem)) {
                $result = array_merge($result, self::flattenParamsList($elem, $calculatedKey));
            } elseif (is_array($elem)) {
                $result = array_merge($result, self::flattenParams($elem, "{$calculatedKey}[{$i}]"));
            } else {
                array_push($result, ["{$calculatedKey}[{$i}]", $elem]);
            }
        }

        return $result;
    }

    /**
     * @param string $key A string to URL-encode.
     *
     * @return string The URL-encoded string.
     */
    public static function urlEncode($key)
    {
        $s = urlencode($key);

        // Don't use strict form encoding by changing the square bracket control
        // characters back to their literals. This is fine by the server, and
        // makes these parameter strings easier to read.
        $s = str_replace('%5B', '[', $s);
        $s = str_replace('%5D', ']', $s);

        return $s;
    }

    public static function normalizeId($id)
    {
        if (is_array($id)) {
            $params = $id;
            $id = $params['id'];
            unset($params['id']);
        } else {
            $params = [];
        }
        return [$id, $params];
    }

    /**
     * Returns UNIX timestamp in milliseconds
     *
     * @return integer current time in millis
     */
    public static function currentTimeMillis()
    {
        return (int) round(microtime(true) * 1000);
    }
}
