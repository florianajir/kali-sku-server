<?php
namespace Meup\Bundle\ApiBundle\Service;

use Meup\Bundle\ApiBundle\Model\SkuInterface;

/**
 * Interface SkuAllocatorInterface
 *
 * @author florianajir <florian@1001pharmacies.com
 */
interface SkuAllocatorInterface
{
    /**
     * Generate and allocate a sku code to be completed in database
     *
     * @param string $project
     *
     * @return SkuInterface
     */
    public function allocate($project);
}
