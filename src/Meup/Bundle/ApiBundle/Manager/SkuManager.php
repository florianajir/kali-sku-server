<?php

namespace Meup\Bundle\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Author: c. Boissieux
 *
 */
class SkuManager
{

    protected $om;
    protected $entity;

    /**
     *
     */
    public function __construct(ObjectManager $om, $entity)
    {
        $this->om = $om;
        $this->entity = $entity;
        $this->repository = $om->getRepository($entity);
    }

    /**
     *
     */
    public function exist()
    {
        $exist = $this->findSkuBy(
            array('id' => $this->entity->getId()
            )
        ;
        return ($exist != null) ? true : false;
    }

    /**
     *
     */
    public function persist(sku $sku,  $andFlush = true)
    {
        $entity = new \ReflexionClass ($sku);
        if (!$entity->isInstantiable()) {
            throw new \Exception(
                sprintf(
                    "the entity '%s' is not instanciable.",
                    $sku
                )
            );
        }
        $this->om->persist($sku);
        if ($andFlush) {
            $this->om->flush();
        }

        return $sku;
    }

    /**
     *
     */
    public function delete(Sku $sku, $andFlush = true)
    {
        $sku->setDeletedAt(new \DateTime());
        $this->om->persist($sku);
        if ($andFlush) {
            $this->om->flush();
        }
    }

    /**
     *
     */
    public function findSkuBy($criteria)
    {
        return $this->repository->findOneBy($criteria);
    }
}