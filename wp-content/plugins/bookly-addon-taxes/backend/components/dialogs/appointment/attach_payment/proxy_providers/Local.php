<?php
namespace BooklyTaxes\Backend\Components\Dialogs\Appointment\AttachPayment\ProxyProviders;

use Bookly\Backend\Components\Dialogs\Appointment\AttachPayment\Proxy;

/**
 * Class Local
 * @package BooklyTaxes\Backend\Components\Dialogs\Appointment\AttachPayment\ProxyProviders
 */
class Local extends Proxy\Taxes
{
    /**
     * @inheritdoc
     */
    public static function renderAttachPayment()
    {
        self::renderTemplate( 'attach_payment' );
    }
}