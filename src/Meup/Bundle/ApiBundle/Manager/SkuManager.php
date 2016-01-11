<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Manager;

use DateTime;
use Meup\Bundle\ApiBundle\Entity\Sku;
use Meup\Bundle\ApiBundle\Factory\SkuFactory;
use Meup\Bundle\ApiBundle\Model\SkuInterface;
use Meup\Bundle\ApiBundle\Repository\SkuRepositoryInterface;
use Meup\Bundle\ApiBundle\Service\CodeGeneratorInterface;

/**
 * Class SkuManager
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
class SkuManager implements SkuManagerInterface
{
    /**
     * Number of max attempts for unique code generation
     */
    const MAX_GENERATION_ATTEMPTS = 5;

    /**
     * @var SkuRepositoryInterface
     */
    protected $repository;

    /**
     * @var SkuFactory
     */
    protected $factory;

    /**
     * @var CodeGeneratorInterface
     */
    protected $codeGenerator;

    /**
     * @param SkuRepositoryInterface $repository
     * @param SkuFactory $factory
     * @param CodeGeneratorInterface $codeGenerator
     */
    public function __construct(
        SkuRepositoryInterface $repository,
        SkuFactory $factory,
        CodeGeneratorInterface $codeGenerator
    ) {
        $this->repository = $repository;
        $this->factory = $factory;
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function allocate($project, $code = null)
    {
        if (null === $code) {
            $code = $this->generateUniqueCode();
        }
        $sku = $this->factory->create($code, $project);
        $this->repository->save($sku);

        return $sku;
    }

    /**
     * {@inheritdoc}
     */
    public function count(array $criteria)
    {
        return $this->repository->countMatching($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(SkuInterface $sku)
    {
        $this->repository->delete($sku);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteWhere(array $criteria)
    {
        return $this->repository->deleteWhere($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function disable(SkuInterface $sku)
    {
        if ($sku instanceof Sku) {
            $sku->setDeletedAt(new DateTime());
        }
        $this->repository->save($sku);

        return $sku;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($skuCode)
    {
        $sku = $this
            ->repository
            ->findOneByCode($skuCode);

        return !empty($sku);
    }

    /**
     * {@inheritdoc}
     */
    public function find($code)
    {
        return $this
            ->repository
            ->findOneByCode($code);
    }

    /**
     * {@inheritdoc}
     */
    public function findByProjectTypeId($project, $objectType, $objectId)
    {
        return $this
            ->repository
            ->findOneByProjectTypeId($project, $objectType, $objectId);
    }

    /**
     * {@inheritdoc}
     */
    public function save(SkuInterface $sku)
    {
        $this->repository->save($sku);

        return $sku;
    }

    /**
     * {@inheritdoc}
     */
    public function generateUniqueCode()
    {
        $attempt = 0;
        $code = null;
        while ($code === null || $this->exists($code)) {
            if ($attempt >= self::MAX_GENERATION_ATTEMPTS) {
                throw new \DomainException('Failed to get unique code: exceeding the maximum number of attempts!');
            }
            $code = $this->codeGenerator->generateSkuCode();
            $attempt++;
        }

        return $code;
    }
}
