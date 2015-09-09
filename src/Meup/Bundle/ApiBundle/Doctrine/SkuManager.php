<?php

namespace Meup\Bundle\ApiBundle\Doctrine;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Meup\Bundle\ApiBundle\Entity\Sku as SkuEntity;
use Meup\Bundle\ApiBundle\Manager\SkuManager as BaseManager;
use Meup\Bundle\ApiBundle\Model\SkuInterface;

/**
 * Class SkuManager
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
class SkuManager extends BaseManager
{
    /**
     * @var ObjectManager
     */
    protected $om;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @param ObjectManager $om
     * @param string        $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->om = $om;
        $this->repository = $om->getRepository($class);
    }

    /**
     * return true if sku code already exists
     *
     * @param string $skuCode
     *
     * @return bool
     */
    public function exists($skuCode)
    {
        $sku = $this->repository->findOneBy(
            array(
                'code' => $skuCode
            )
        );

        return !empty($sku);
    }

    /**
     * @param SkuInterface $sku
     * @param bool         $andFlush
     *
     * @return SkuInterface
     */
    public function persist(SkuInterface $sku, $andFlush = true)
    {
        $this->om->persist($sku);
        if ($andFlush) {
            $this->om->flush();
        }

        return $sku;
    }

    /**
     * Get a sku by his code
     *
     * @param string $skuCode
     *
     * @return SkuInterface
     */
    public function getByCode($skuCode)
    {
        $sku = $this->repository->findOneBy(
            array(
                'code' => $skuCode
            )
        );

        return $sku;
    }

    /**
     * @param SkuInterface $sku
     * @param bool         $andFlush
     *
     * @return SkuInterface
     */
    public function delete(SkuInterface $sku, $andFlush = true)
    {
        if ($sku instanceof SkuEntity) {
            $sku->setDeletedAt(new DateTime());
        }
        $this->om->persist($sku);
        if ($andFlush) {
            $this->om->flush();
        }

        return $sku;
    }
}
