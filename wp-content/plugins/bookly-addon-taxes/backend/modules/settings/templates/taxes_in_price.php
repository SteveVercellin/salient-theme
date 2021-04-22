<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Settings\Selects;
use BooklyTaxes\Lib\Entities\Tax;
?>
<div class="form-group">
    <?php Selects::renderSingle( 'bookly_taxes_in_price', __( 'Price settings and display', 'bookly' ), __( 'If the prices for your services include taxes, select include taxes. If the prices for your services do not include taxes, select exclude taxes.', 'bookly' ), array(
        array( Tax::TAX_INCLUDED, __( 'Include taxes', 'bookly' ) ),
        array( Tax::TAX_EXCLUDED, __( 'Exclude taxes', 'bookly' ) ),
    ) ) ?>
</div>