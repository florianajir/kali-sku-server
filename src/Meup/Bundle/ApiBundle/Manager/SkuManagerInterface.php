<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Manager;

use Meup\Bundle\ApiBundle\Model\SkuInterface;

/**
 * Interface SkuManagerInterface
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
interface SkuManagerInterface
{
    /**
     * Allocate a new sku with unique code and save it in database (attributes to complete furtherly)
     *
     * @param string $project project name hosting object
     * @param string $code    sku code, if null it will be generated
     *
     * @return SkuInterface
     */
    public function allocate($project, $code = null);

    /**
     * Count matching criteria records
     *
     * @param array $criteria
     *
     * @return int
     */
    public function count(array $criteria);

    /**
     * Delete a sku
     *
     * @param SkuInterface $sku
     */
    public function delete(SkuInterface $sku);

    /**
     * Delete sku matching with criteria
     *
     * @param array $criteria
     */
    public function deleteWhere(array $criteria);

    /**
     * Disable a sku
     *
     * @param SkuInterface $sku
     *
     * @return SkuInterface
     */
    public function disable(SkuInterface $sku);

    /**
     * Return true if a sku with the matching code exists
     *
     * @param string $code
     *
     * @return bool
     */
    public function exists($code);

    /**
     * Find a sku by his code or false if not exists
     *
     * @param string $code
     *
     * @return SkuInterface
     */
    public function find($code);

    /**
     * Find a sku with matching project, type and id attributes
     *
     * @param string $project    Project name
     * @param string $objectType Object type
     * @param int    $objectId   Object id
     *
     * @return SkuInterface
     */
    public function findByProjectTypeId($project, $objectType, $objectId);

    /**
     * Generate sku code until find an unused and return it
     *
     * @return string
     *
     * @throws /DomainException
     */
    public function generateUniqueCode();

    /**
     * @param SkuInterface $sku
     *
     * @return SkuInterface
     */
    public function save(SkuInterface $sku);
}
