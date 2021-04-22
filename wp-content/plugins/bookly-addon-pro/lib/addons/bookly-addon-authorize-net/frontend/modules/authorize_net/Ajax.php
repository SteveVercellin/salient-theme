<?php
namespace BooklyAuthorizeNet\Frontend\Modules\AuthorizeNet;

use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\Booking\Lib\Errors;
use BooklyAuthorizeNet\Lib;

/**
 * Class Ajax
 * @package BooklyAuthorizeNet\Frontend\Modules\AuthorizeNet
 */
class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * @inheritdoc
     */
    protected static function permissions()
    {
        return array( '_default' => 'anonymous' );
    }

    /**
     * Do AIM payment.
     */
    public static function aimPayment()
    {
        $response = null;
        $userData = new BooklyLib\UserBookingData( self::parameter( 'form_id' ) );

        if ( $userData->load() ) {
            $failed_cart_key = $userData->cart->getFailedKey();
            if ( $failed_cart_key === null ) {
                $card       = self::parameter( 'card' );
                $first_name = $userData->getFirstName();
                $last_name  = $userData->getLastName();
                // Check if defined First name
                if ( ! $first_name ) {
                    $full_name  = $userData->getFullName();
                    $first_name = strtok( $full_name, ' ' );
                    $last_name  = strtok( '' );
                }
                $cart_info  = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_AUTHORIZENET );
                $cart_info->setGatewayTaxCalculationRule( 'tax_in_the_price' );
                // Authorize.Net AIM Payment.
                $authorize = new Lib\Payment\AuthorizeNet( get_option( 'bookly_authorize_net_api_login_id' ), get_option( 'bookly_authorize_net_transaction_key' ), (bool) get_option( 'bookly_authorize_net_sandbox' ) );
                $authorize->setField( 'amount', $cart_info->getGatewayAmount() )
                    ->setField( 'card_num',     $card['number'] )
                    ->setField( 'card_code',    $card['cvc'] )
                    ->setField( 'exp_date',     $card['exp_month'] . '/' . $card['exp_year'] )
                    ->setField( 'email',        $userData->getEmail() )
                    ->setField( 'phone',        $userData->getPhone() )
                    ->setField( 'first_name',   $first_name )
                    ->setField( 'tax',          $cart_info->getGatewayTax() );
                if ( $last_name ) {
                    $authorize->setField( 'last_name', $last_name );
                }

                $aim_response = $authorize->authorizeAndCapture();
                if ( $aim_response->approved ) {
                    $coupon = $userData->getCoupon();
                    if ( $coupon ) {
                        $coupon->claim();
                        $coupon->save();
                    }
                    $payment = new BooklyLib\Entities\Payment();
                    $payment->setType( BooklyLib\Entities\Payment::TYPE_AUTHORIZENET )
                        ->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )
                        ->setCartInfo( $cart_info )
                        ->save();
                    $order = $userData->save( $payment );
                    $payment->setDetailsFromOrder( $order, $cart_info )->save();
                    BooklyLib\Notifications\Cart\Sender::send( $order );
                    $response = array( 'success' => true );
                } else {
                    $response = array( 'success' => false, 'error' => Errors::PAYMENT_ERROR, 'error_message' => $aim_response->response_reason_text );
                }
            } else {
                $response = array(
                    'success'         => false,
                    'error'           => Errors::CART_ITEM_NOT_AVAILABLE,
                    'failed_cart_key' => $failed_cart_key,
                );
            }
        } else {
            $response = array( 'success' => false, 'error' => Errors::SESSION_ERROR );
        }
        $userData->sessionSave();

        wp_send_json( $response );
    }
}
