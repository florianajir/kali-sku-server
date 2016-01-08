<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Doctrine;

use Doctrine\ORM\EntityRepository;
use Meup\Bundle\ApiBundle\Model\SkuInterface;
use Meup\Bundle\ApiBundle\Repository\SkuRepositoryInterface;

/**
 * Class SkuRepository
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
class SkuRepository extends EntityRepository implements SkuRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findOneByCode($code)
    {
        return $this->findOneBy(
            array(
                'code' => $code
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByProjectTypeId($project, $foreignType, $foreignId)
    {
        return $this->findOneBy(
            array(
                'project' => $project,
                'foreignType' => $foreignType,
                'foreignId' => $foreignId
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function save(SkuInterface $sku)
    {
        $this->getEntityManager()->persist($sku);
        $this->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(SkuInterface $sku)
    {
        $this->getEntityManager()->remove($sku);
        $this->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function countMatching(array $criteria)
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->select('count(s)');
        foreach ($criteria as $field => $value) {
            $qb
                ->andWhere(sprintf('s.%s = :%s', $field, $field))
                ->setParameter($field, $value);
        }

        return $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteWhere(array $criteria)
    {
        $qb = $this
            ->createQueryBuilder('s')
            ->delete()
        ;
        foreach ($criteria as $field => $value) {
            $qb
                ->andWhere(sprintf('s.%s = :%s', $field, $field))
                ->setParameter($field, $value);
        }

        return $qb
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Flush the entity manager
     */
    private function flush()
    {
        $this->getEntityManager()->flush();
    }
}
