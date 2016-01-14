<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Model;

/**
 * provide an interface for Skus
 *
 * @author Florian Ajir <florian@1001pharmacies.com>
 * @author c. Boissieux <christophe@1001pharmacies.com>
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
     * Get project
     *
     * @return string
     */
    public function getProject();

    /**
     * Set project property
     *
     * @param string $project
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
     *
     * @return self
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
     *
     * @return self
     */
    public function setForeignId($foreignId);

    /**
     * @return string
     */
    public function getPermalink();

    /**
     * @param string $permalink
     *
     * @return self
     */
    public function setPermalink($permalink);

    /**
     * @return bool
     */
    public function isActive();
}
