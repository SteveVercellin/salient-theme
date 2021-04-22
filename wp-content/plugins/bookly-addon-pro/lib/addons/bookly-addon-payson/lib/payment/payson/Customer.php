<?php
namespace BooklyPayson\Lib\Payment\Payson;

/**
 * Class Customer
 * @package BooklyPayson\Lib\Payment\Payson
 */
class Customer
{
    /** @var string $city */
    public $city;
    /** @var string $country_code */
    public $country_code;
    /** @var int $identity_number */
    public $identity_number;
    /** @var string $email */
    public $email;
    /** @var string $first_name */
    public $first_name;
    /** @var string $last_name */
    public $last_name;
    /** @var string $phone Phone number. */
    public $phone;
    /** @var string $postal_code Postal code. */
    public $postal_code;
    /** @var string $street Street address. */
    public $street;
    /** @var string $type Type of customer ("business", "person" (default)). */
    public $type;

    /**
     * Customer constructor.
     *
     * @param string $first_name
     * @param string $last_name
     * @param string $email
     * @param string $phone
     * @param null   $identity_number
     * @param null   $city
     * @param null   $country_code
     * @param null   $postal_code
     * @param null   $street
     * @param string $type
     */
    public function __construct( $first_name, $last_name, $email, $phone, $identity_number = null, $city = null, $country_code = null, $postal_code = null, $street = null, $type = 'person' )
    {
        $this->first_name      = $first_name;
        $this->last_name       = $last_name;
        $this->email           = $email;
        $this->phone           = $phone;
        $this->identity_number = $identity_number;
        $this->city            = $city;
        $this->country_code    = $country_code;
        $this->postal_code     = $postal_code;
        $this->street          = $street;
        $this->type            = $type;
    }

    /**
     * @param \stdClass $data
     * @return Customer
     */
    public static function create( $data )
    {
        return new Customer( $data->firstName, $data->lastName, $data->email, $data->phone, $data->identityNumber, $data->city, $data->countryCode, $data->postalCode, $data->street, $data->type );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'firstName'      => $this->first_name,
            'lastName'       => $this->last_name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'identityNumber' => $this->identity_number,
            'city'           => $this->city,
            'countryCode'    => $this->country_code,
            'postalCode'     => $this->postal_code,
            'street'         => $this->street,
            'type'           => $this->type,
        );
    }
}