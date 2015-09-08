<?php

namespace Meup\Bundle\ApiBundle\Model;

/**
 * provide an interface for Skus
 *
 * @author  c. Boissieux <christophe@1001pharmacies.com>
 *
 */
interface SkuInterface
{
    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set code
     *
     * @param string $code
     * @return Sku
     */
    public function setCode($code);

    /**
     * Get project
     *
     * @return string
     */
    public function getProject();

    /**
     * Set project
     *
     * @param string $project
     * @return Sku
     */
    public function setProject($project);

    /**
     * Get foreignerType
     *
     * @return string
     */
    public function getForeignType();

    /**
     * Set foreignType
     *
     * @param string $foreignType
     * @return Sku
     */
    public function setforeignType($foreignType);

    /**
     * Get foreignId
     *
     * @return string
     */
    public function getForeignId();

    /**
     * Set foreignId
     *
     * @param string $foreignId
     * @return Sku
     */
    public function setForeignId($foreignId);
}