<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/** @var Bookly\Lib\CartInfo $cart_info */
use Bookly\Lib\Utils;
?>
<div class="bookly-box bookly-list">
    <label>
        <input type="radio" class="bookly-payment" name="payment-method-<?php echo $form_id ?>" value="payson"/>
        <span><?php echo Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_payson' ) ?>
            <?php if ( $show_price ) : ?>
                <span class="bookly-js-pay"><?php echo Utils\Price::format( $cart_info->getPayNow() ) ?></span>
            <?php endif ?>
        </span>
        <img src="<?php echo $url_cards_image ?>" alt="cards" />
    </label>
    <?php if ( is_array( $payment_status ) && $payment_status['gateway'] == Bookly\Lib\Entities\Payment::TYPE_PAYSON && $payment_status['status'] == 'error' ) : ?>
        <div class="bookly-label-error" style="padding-top: 5px;">* <?php echo $payment_status['data'] ?></div>
    <?php endif ?>
</div>