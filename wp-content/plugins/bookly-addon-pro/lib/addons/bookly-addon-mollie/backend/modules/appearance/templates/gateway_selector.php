<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Appearance\Editable;
?>
<div class="bookly-box bookly-list">
    <label>
        <input type="radio" name="payment" />
        <?php Editable::renderString( array( 'bookly_l10n_label_pay_mollie', ), 'Mollie' ) ?>
        <img src="<?php echo plugins_url( 'frontend/resources/images/mollie.png', BooklyMollie\Lib\Plugin::getMainFile() ) ?>" alt="mollie" />
    </label>
</div>