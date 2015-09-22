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
 * @author Loïc Ambrosini <loic@1001pharmacies.com>
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
     * {@inheritdoc}
     */
    public function findByUniqueGroup($project, $foreignType, $foreignId)
    {
        return $this
            ->repository
            ->findOneBy(
                array(
                    'project'       => $project,
                    'foreignType'   => $foreignType,
                    'foreignId'     => $foreignId
                )
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($skuCode)
    {
        $sku = $this
            ->repository
            ->findOneBy(
                array(
                    'code' => $skuCode
                )
            )
        ;

        return !empty($sku);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getByCode($skuCode)
    {
        $sku = $this
            ->repository
            ->findOneBy(
                array(
                    'code' => $skuCode
                )
            )
        ;

        return $sku;
    }

    /**
     * {@inheritdoc}
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
