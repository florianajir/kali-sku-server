<?php
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
     * return true if sku code already exists
     *
     * @param string $skuCode
     *
     * @return bool
     */
    public function exists($skuCode);

    /**
     * @param SkuInterface $sku
     *
     * @return SkuInterface
     */
    public function create(SkuInterface $sku);

    /**
     * Get a sku by his code or false if not exists
     *
     * @param string $skuCode
     *
     * @return SkuInterface|bool
     */
    public function getByCode($skuCode);

    /**
     * @param SkuInterface $sku
     * @param bool         $andFlush
     *
     * @return SkuInterface
     */
    public function persist(SkuInterface $sku, $andFlush = true);

    /**
     * Delete a sku
     *
     * @param SkuInterface $sku
     * @param bool         $andFlush
     *
     * @return SkuInterface
     */
    public function delete(SkuInterface $sku, $andFlush = true);
}
