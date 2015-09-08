<?php

namespace Meup\Bundle\ApiBundle\Model;


/**
 * Author: c. Boissieux
 *
 */

class Sku {

    protected $code;

    protected $project;

    protected $foreignType;

    protected $foreignId;


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
    public function setCode($code)
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
    public function getForeignType()
    {
        return $this->foreignType;
    }

    /**
     * Set foreignType
     *
     * @param string $foreignType
     * @return Sku
     */
    public function setforeignType($foreignType)
    {
        $this->foreignType = $foreignType;
        return $this;
    }

    /**
     * Get foreignId
     *
     * @return string
     */
    public function getForeignId()
    {
        return $this->foreignId;
    }

    /**
     * Set foreignId
     *
     * @param string $foreignId
     * @return Sku
     */
    public function setForeignId($foreignId)
    {
        $this->foreignId = $foreignId;
        return $this;
    }

}