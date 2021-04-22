<?php
namespace BooklyPayson\Lib\Payment\Payson;

/**
 * Class Api
 * @package BooklyPayson\Lib\Payment\Payson
 */
class Api
{
    /** @var int */
    private $merchant_id;
    /** @var string */
    private $api_key;
    /** @var bool */
    private $sandbox;

    const HOST_API = 'api.payson.se/2.0/';

    const ACTION_CHECKOUTS = 'Checkouts/';
    const ACTION_ACCOUNTS  = 'Accounts/';

    public function __construct( $merchant_id, $api_key, $sandbox = false )
    {
        $this->sandbox     = $sandbox;
        $this->merchant_id = $merchant_id;
        $this->api_key     = $api_key;
    }

    /**
     * @param Checkout $checkout
     * @return string
     * @throws \Exception
     */
    public function createCheckout( Checkout $checkout )
    {
        $response = $this->sendRequest( 'POST', $this->getApiUrl( self::ACTION_CHECKOUTS ), $checkout->toArray() );

        return $response->id;
    }

    /**
     * @param string $checkout_id
     * @return Checkout
     * @throws \Exception
     */
    public function getCheckout( $checkout_id )
    {
        $result = $this->sendRequest( 'GET', $this->getApiUrl( self::ACTION_CHECKOUTS ) . $checkout_id, array() );

        return Checkout::create( $result );
    }

    /**
     * Send API request
     *
     * @param string $method Request method
     * @param string $url
     * @param array  $data
     * @return \stdClass
     * @throws \Exception
     */
    private function sendRequest( $method, $url, $data = array() )
    {
        $args = array(
            'method'  => $method,
            'timeout' => 30,
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode( $this->merchant_id . ':' . $this->api_key ),
            ),
        );

        if ( $method == 'GET' ) {
            // WP 4.4.11 doesn't take into account the $data for the GET request
            // Manually move data in query string
            $url = add_query_arg( $data, $url );
        } else {
            $args['body'] = json_encode( $data );
        }

        $response = wp_remote_request( $url, $args );
        if ( is_wp_error( $response ) ) {

            throw new \Exception( 'Request failed' );
        } else {
            /* This class of status codes indicates the action requested by the client was received, understood, accepted and processed successfully
             * 200 OK
             * 201 Created
             * 202 Accepted
             * 203 Non-Authoritative Information (since HTTP/1.1)
             */
            switch ( $response['response']['code'] ) {
                case 200:
                case 201:
                    return json_decode( $response['body'] );
                default:
                    $data     = json_decode( $response['body'], true );
                    $errors[] = 'HTTP status code: ' . $response['response']['code'] . '.';

                    if ( isset( $data['errors'] ) ) {
                        foreach ( $data['errors'] as $error ) {
                            $errors[] = $error['message'];
                        }
                    }
                    if ( array_key_exists( 'message', $data ) ) {
                        $errors[] = $data['message'];
                    }

                    throw new \Exception( implode( PHP_EOL, $errors ) );
            }
        }
    }

    /**
     * @param string $action
     * @return string
     */
    private function getApiUrl( $action )
    {
        return 'https://' . ( $this->sandbox ? 'test-' : '' ) . self::HOST_API . $action;
    }

    /**
     * @param string $checkout_id
     * @return string
     */
    public function getEmbeddedCheckoutUrl( $checkout_id )
    {
        if ( $this->sandbox ) {
            return 'https://test-www.payson.se/embedded/checkout?id=' . $checkout_id;
        } else {
            return 'https://embedded.payson.se/checkout?id=' . $checkout_id;
        }
    }

}
