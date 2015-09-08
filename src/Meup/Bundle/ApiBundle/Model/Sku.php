<?php

namespace Meup\Bundle\ApiBundle\Model;


/**
 * @author  c. Boissieux <christophe@1001pharmacies.com>
 *
 */

class Sku implements SkuInterface
{

    protected $code;

    protected $project;

    protected $foreignType;

    protected $foreignId;


    /**
     * {@inheritDoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritDoc}
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * {@inheritDoc}
     */
    public function setProject($project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getForeignType()
    {
        return $this->foreignType;
    }

    /**
     * {@inheritDoc}
     */
    public function setforeignType($foreignType)
    {
        $this->foreignType = $foreignType;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getForeignId()
    {
        return $this->foreignId;
    }

    /**
     * {@inheritDoc}
     */
    public function setForeignId($foreignId)
    {
        $this->foreignId = $foreignId;
        return $this;
    }

}