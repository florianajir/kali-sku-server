<?php
namespace Meup\Bundle\ApiBundle\Model;

/**
 * Author: c. Boissieux
 *
 */
class Sku
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $project;

    /**
     * @var string
     */
    protected $foreignType;

    /**
     * @var string
     */
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
     *
     * @return self
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
     *
     * @return self
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
     *
     * @return self
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
     *
     * @return self
     */
    public function setForeignId($foreignId)
    {
        $this->foreignId = $foreignId;

        return $this;
    }
}
