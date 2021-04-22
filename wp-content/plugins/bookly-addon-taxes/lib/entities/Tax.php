<?php
namespace BooklyTaxes\Lib\Entities;

use Bookly\Lib;

/**
 * Class Tax
 * @package BooklyTaxes\Lib\Entities
 */
class Tax extends Lib\Base\Entity
{
    const TAX_INCLUDED = 'included';
    const TAX_EXCLUDED = 'excluded';

    protected static $table = 'bookly_taxes';

    /** @var  string */
    protected $title;
    /** @var  double */
    protected $rate;

    protected static $schema = array(
        'id'    => array( 'format' => '%d' ),
        'title' => array( 'format' => '%s' ),
        'rate'  => array( 'format' => '%f' ),
    );

    /**************************************************************************
     * Entity Fields Getters & Setters                                        *
     **************************************************************************/

    /**
     * Gets title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets rate
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets rate
     *
     * @param float $rate
     * @return $this
     */
    public function setRate( $rate )
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @param string $locale
     * @return string
     */
    public function getTranslatedTitle( $locale = null )
    {
        return Lib\Utils\Common::getTranslatedString( 'tax_' . $this->getId(), $this->getTitle(), $locale );
    }

    /**************************************************************************
     * Overridden Methods                                                     *
     **************************************************************************/

    public function save()
    {
        $return = parent::save();
        if ( $this->isLoaded() ) {
            // Register string for translate in WPML.
            do_action( 'wpml_register_single_string', 'bookly', 'tax_' . $this->getId(), $this->getTitle() );
        }
        return $return;
    }

}
