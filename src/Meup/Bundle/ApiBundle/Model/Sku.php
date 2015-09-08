<?php

namespace Meup\Bundle\ApiBundle\Model;

use Meup\Bundle\ApiBundle\Entity\Sku;

/**
 * Author: c. Boissieux
 *
 */

class Sku {

    protected $code;

    protected $project;

    protected $foreignerType;

    protected $foreignerId;


    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Sku
     */
    public function setSku($code)
    {
        $this->code = $code;
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
     * @return Sku
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
     * @return Sku
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
     * @return Sku
     */
    public function setForeignerId($foreignerId)
    {
        $this->foreignerId = $foreignerId;
        return $this;
    }

}