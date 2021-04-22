<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Modules\Appearance\Proxy;
?>
<div class="bookly-js-cart-settings collapse">
    <div class="row">
        <?php Proxy\ServiceExtras::renderShowCartExtras() ?>
    </div>
</div>