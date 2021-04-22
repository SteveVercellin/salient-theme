<?php
namespace Bookly\Backend\Components\Info\Rate;

use Bookly\Lib;
use Bookly\Backend\Modules;
use Bookly\Backend\Components\Info\Base;
use Bookly\Backend\Components\Support\Lib\Urls;

/**
 * Class Notice
 * @package Bookly\Backend\Components\Info\Rate
 */
class Notice extends Base\Notice
{
    /** @inheritDoc */
    public static function create( $id )
    {
        return parent::create( $id )
            ->setMessage(
                __( 'Could you please do me a BIG favor and give it a 5-star rating on WordPress?', 'bookly' ) . '<br>' .
                __( 'Just to help us spread the word and boost our motivation.', 'bookly' )
            )
            ->addMainButton( __( 'Ok, you deserve it', 'bookly' ), 'bookly-js-ok' )
            ->addDefaultButton( __( 'Nope, maybe later', 'bookly' ), 'bookly-js-maybe-later' )
            ->setDissmisClass( 'bookly-js-dismiss' );
    }

    /** @inheritDoc */
    public function render()
    {
        $hide_until = (int) $this->getUserMeta( 'bookly_notice_rate_on_wp_hide_until' );
        if ( time() >= $hide_until ) {
            self::enqueueScripts( array(
                'backend'  => array(
                    'js/alert.js' => array( 'jquery' ),
                ),
                'frontend' => array(
                    'js/spin.min.js'  => array( 'jquery' ),
                    'js/ladda.min.js' => array( 'jquery' ),
                ),
                'module'   => array(
                    'js/rate.js' => array( 'bookly-alert.js', 'bookly-ladda.min.js', ),
                ),
            ) );

            if ( $hide_until === 0 ) {
                $rate = $this->getUserMeta( 'bookly_nps_rate' );
                if ( $rate ) {
                    $this->setTitle( strtr( __( 'Thank you for the {star} star rating!!!', 'bookly' ), array( '{star}' => $rate ) ) );
                } else {
                    $this
                        ->setTitle( __( 'Thank you for the {star} star rating!!!', 'bookly' ) )
                        ->hidden();
                }
            } elseif ( $this->getUserMeta( 'bookly_notice_rate_on_wp_remember_me' ) ) {
                $this->setTitle( __( 'You asked to remind you', 'bookly' ) );
            } else switch ( get_option( 'bookly_сa_count' ) ) {
                case 0:     break;
                case 10:    $this->setTitle( __( 'Congratulations!!!', 'bookly' ), __( 'You just made the <b>10th Sale</b> using Bookly Plugin!', 'bookly' ) );    break;
                case 100:   $this->setTitle( __( 'Congratulations!!!', 'bookly' ), __( 'You just made the <b>100th Sale</b> using Bookly Plugin!', 'bookly' ) );   break;
                case 1000:  $this->setTitle( __( 'Congratulations!!!', 'bookly' ), __( 'You just made the <b>1000th Sale</b> using Bookly Plugin!', 'bookly' ) );  break;
                case 10000:
                default:    $this->setTitle( __( 'Congratulations!!!', 'bookly' ), __( 'You just made the <b>10000th Sale</b> using Bookly Plugin!', 'bookly' ) ); break;
            }

            wp_localize_script( 'bookly-rate.js', 'BooklyRateL10n', array(
                'csrfToken'  => Lib\Utils\Common::getCsrfToken(),
                'reviewsUrl' => Lib\Utils\Common::prepareUrlReferrers( Urls::REVIEWS_PAGE, 'notification_bar' ),
            ) );
            parent::render();
        }
    }
}