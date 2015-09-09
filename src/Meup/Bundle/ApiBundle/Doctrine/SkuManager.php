<?php

namespace Meup\Bundle\ApiBundle\Doctrine;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Meup\Bundle\ApiBundle\Entity\Sku as SkuEntity;
use Meup\Bundle\ApiBundle\Manager\SkuManager as BaseManager;
use Meup\Bundle\ApiBundle\Model\SkuInterface;
use Meup\Bundle\ApiBundle\Service\SkuCodeGeneratorInterface;

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
     * @var SkuCodeGeneratorInterface
     */
    protected $skuCodeGenerator;

    /**
     * @param string                    $class
     * @param ObjectManager             $om
     * @param SkuCodeGeneratorInterface $skuCodeGenerator
     */
    public function __construct(
        $class,
        ObjectManager $om,
        SkuCodeGeneratorInterface $skuCodeGenerator
    ) {
        $this->om = $om;
        $this->repository = $om->getRepository($class);
        $this->skuCodeGenerator = $skuCodeGenerator;
    }

    /**
     * @param SkuInterface $sku
     *
     * @return SkuInterface
     */
    public function create(SkuInterface $sku)
    {
        $sku->setCode(null);

        while ($sku->getCode() === null || $this->exists($sku->getCode())) {
            $sku->setCode($this->skuCodeGenerator->generateSkuCode());
        }

        return $this->persist($sku);
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
     * @return SkuInterface|bool
     */
    public function getByCode($skuCode)
    {
        $sku = $this->repository->findBy(
            array(
                'code' => $skuCode
            )
        );

        return empty($sku) ? false : $sku;
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
