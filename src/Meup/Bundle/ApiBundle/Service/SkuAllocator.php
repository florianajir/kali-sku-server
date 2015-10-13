<?php
namespace Meup\Bundle\ApiBundle\Service;

use Meup\Bundle\ApiBundle\Factory\SkuFactory;
use Meup\Bundle\ApiBundle\Manager\SkuManagerInterface;
use Meup\Bundle\ApiBundle\Model\SkuInterface;

/**
 * Class SkuCodeGenerator
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
class SkuAllocator implements SkuAllocatorInterface
{
    /**
     * @var SkuFactory
     */
    protected $skuFactory;

    /**
     * @var SkuManagerInterface
     */
    protected $skuManager;

    /**
     * @var SkuCodeGeneratorInterface
     */
    protected $skuCodeGenerator;

    /**
     * @param SkuFactory $skuFactory
     * @param SkuManagerInterface $skuManager
     * @param SkuCodeGeneratorInterface $skuCodeGenerator
     */
    public function __construct(
        SkuFactory $skuFactory,
        SkuManagerInterface $skuManager,
        SkuCodeGeneratorInterface $skuCodeGenerator
    ) {
        $this->skuFactory = $skuFactory;
        $this->skuManager = $skuManager;
        $this->skuCodeGenerator = $skuCodeGenerator;
    }

    /**
     * Allocate a sku code to be completed
     *
     * @param string $project requester web-app
     *
     * @return SkuInterface
     */
    public function allocate($project)
    {
        $sku = $this->skuFactory->create();
        $sku->setProject($project);
        while ($sku->getCode() === null || $this->skuManager->exists($sku->getCode())) {
            $sku->setCode($this->skuCodeGenerator->generateSkuCode());
        }
        $this->skuManager->persist($sku);

        return $sku;
    }
}
