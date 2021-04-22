<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="bookly-form-group">
    <label><?php echo \Bookly\Lib\Utils\Common::getTranslatedOption( 'bookly_l10n_label_number_of_persons' ) ?></label>
    <div>
        <select class="bookly-select-mobile bookly-js-select-number-of-persons">
            <option value="1">1</option>
        </select>
    </div>
</div>