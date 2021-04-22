<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/** @var Bookly\Lib\CartInfo $cart_info */
use Bookly\Lib\Utils;
?>
<div class="bookly-box bookly-list">
    <label>
        <input type="radio" class="bookly-payment" name="payment-method-<?php echo $form_id ?>" value="card" data-form="stripe" />
        <span><?php echo Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_stripe' ) ?>
            <?php if ( $show_price ) : ?>
                <span class="bookly-js-pay"><?php echo Utils\Price::format( $cart_info->getPayNow() ) ?></span>
            <?php endif ?>
        </span>
        <img src="<?php echo $url_cards_image ?>" alt="cards" />
    </label>
    <form class="bookly-stripe" style="display: none; margin-top: 15px;">
        <div class="bookly-form-group">
            <div id="bookly-stripe-card-field" style="padding: 10px; border: 1px solid silver; max-width: 400px;"></div>
        </div>
        <div class="bookly-label-error bookly-js-card-error"></div>
    </form>
</div>