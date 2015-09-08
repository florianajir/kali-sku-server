<?php

namespace Meup\Bundle\ApiBundle\Model;

use Meup\Bundle\ApiBundle\Entity\Sku;

/**
 * Author: c. Boissieux
 *
 */

class Identifier {

    protected $sku;

    protected $project;

    protected $foreignerType;

    protected $foreignerId;


    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return Identifier
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Get project
     *
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project
     *
     * @param string $project
     * @return Identifier
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get foreignerType
     *
     * @return string
     */
    public function getForeignerType()
    {
        return $this->foreignerType;
    }

    /**
     * Set foreignerType
     *
     * @param string $foreignerType
     * @return Identifier
     */
    public function setForeignerType($foreignerType)
    {
        $this->foreignerType = $foreignerType;
        return $this;
    }

    /**
     * Get foreignerId
     *
     * @return string
     */
    public function getForeignerId()
    {
        return $this->foreignerId;
    }

    /**
     * Set foreignerId
     *
     * @param string $foreignerId
     * @return Identifier
     */
    public function setForeignerId($foreignerId)
    {
        $this->foreignerId = $foreignerId;
        return $this;
    }

}