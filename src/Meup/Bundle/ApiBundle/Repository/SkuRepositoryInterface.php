<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Repository;

use Meup\Bundle\ApiBundle\Model\SkuInterface;

/**
 * Provide an interface for SkuRepository
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
interface SkuRepositoryInterface
{
    /**
     * Persist and flush a sku
     *
     * @param SkuInterface $sku
     */
    public function save(SkuInterface $sku);

    /**
     * Find a sku by his code
     *
     * @param string $code
     *
     * @return SkuInterface
     */
    public function findOneByCode($code);

    /**
     * Find a sku with matching project, foreign_type and foreign_id attributes
     *
     * @param string $project
     * @param string $foreignType
     * @param int    $foreignId
     *
     * @return SkuInterface
     */
    public function findOneByProjectTypeId($project, $foreignType, $foreignId);

    /**
     * Delete a sku
     *
     * @param SkuInterface $sku
     */
    public function delete(SkuInterface $sku);

    /**
     * Count matching criteria records
     *
     * @param array $criteria
     *
     * @return int
     */
    public function countMatching(array $criteria);

    /**
     * Delete sku matching with criteria
     *
     * @param array $criteria
     */
    public function deleteWhere(array $criteria);
}
