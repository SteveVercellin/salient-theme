<?php
namespace BooklyTaxes\Lib\Entities;

use Bookly\Lib;

/**
 * Class ServiceTax
 * @package BooklyTaxes\Lib\Entities
 */
class ServiceTax extends Lib\Base\Entity
{
    protected static $table = 'bookly_service_taxes';

    /** @var  int */
    protected $service_id;
    /** @var  int */
    protected $tax_id;

    protected static $schema = array(
        'id'         => array( 'format' => '%d' ),
        'service_id' => array( 'format' => '%d', 'reference' => array( 'entity' => 'Service', 'namespace' => '\Bookly\Lib\Entities' ) ),
        'tax_id'     => array( 'format' => '%d', 'reference' => array( 'entity' => 'Tax' ) ),
    );

    /**************************************************************************
     * Entity Fields Getters & Setters                                        *
     **************************************************************************/

    /**
     * Gets service_id
     *
     * @return int
     */
    public function getServiceId()
    {
        return $this->service_id;
    }

    /**
     * Sets service_id
     *
     * @param int $service_id
     * @return $this
     */
    public function setServiceId( $service_id )
    {
        $this->service_id = $service_id;

        return $this;
    }

    /**
     * Gets tax_id
     *
     * @return int
     */
    public function getTaxId()
    {
        return $this->tax_id;
    }

    /**
     * Sets tax_id
     *
     * @param int $tax_id
     * @return $this
     */
    public function setTaxId( $tax_id )
    {
        $this->tax_id = $tax_id;

        return $this;
    }

    /**************************************************************************
     * Overridden Methods                                                     *
     **************************************************************************/

}
