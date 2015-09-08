<?php

namespace Meup\Bundle\ApiBundle\Doctrine;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Meup\Bundle\ApiBundle\Entity\Sku;
use Meup\Bundle\ApiBundle\Manager\SkuManager as BaseManager;

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
        $sku = $this->repository->findBy(
            array(
                'code' => $skuCode
            )
        );

        return !empty($sku);
    }

    /**
     * @param Sku  $sku
     * @param bool $andFlush
     *
     * @return Sku
     */
    public function persist(Sku $sku, $andFlush = true)
    {
        $this->om->persist($sku);
        if ($andFlush) {
            $this->om->flush();
        }

        return $sku;
    }

    /**
     * @param Sku  $sku
     * @param bool $andFlush
     *
     * @return Sku
     */
    public function delete(Sku $sku, $andFlush = true)
    {
        $sku->setDeletedAt(new DateTime());
        $this->om->persist($sku);
        if ($andFlush) {
            $this->om->flush();
        }

        return $sku;
    }
}
