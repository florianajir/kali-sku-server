<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Tests\Doctrine;

use Meup\Bundle\ApiBundle\DataFixtures\ORM\LoadSkuData;
use Meup\Bundle\ApiBundle\Doctrine\SkuRepository;
use Meup\Bundle\ApiBundle\Entity\Sku;
use Meup\Bundle\ApiBundle\Tests\Fixtures\FixtureAwareTestCase;

/**
 * Class SkuRepositoryTest
 *
 * @author Florian Ajir <florian@1001pharmacies.com>
 */
class SkuRepositoryTest extends FixtureAwareTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Base fixture for all tests
        $this->addFixture(new LoadSkuData());
        $this->executeFixtures();
    }

    /**
     * Test the findOneByCode repository function
     */
    public function testFindOneByCode()
    {
        $result = $this
            ->getRepository()
            ->findOneByCode(LoadSkuData::CODE)
        ;
        $this->assertNotNull($result);
        $this->assertEquals(LoadSkuData::PROJECT, $result->getProject());
        $this->assertEquals(LoadSkuData::TYPE, $result->getForeignType());
        $this->assertEquals(LoadSkuData::OBJECT_ID, $result->getForeignId());
    }

    /**
     * Test the findOneByCode repository function
     */
    public function testFindOneByProjectTypeId()
    {
        $result = $this
            ->getRepository()
            ->findOneByProjectTypeId(LoadSkuData::PROJECT, LoadSkuData::TYPE, LoadSkuData::OBJECT_ID)
        ;
        $this->assertNotNull($result);
    }

    /**
     * Test the repository save function
     */
    public function testSave()
    {
        $code = uniqid();
        $project = uniqid();
        $sku = new Sku($code, $project);
        $this
            ->getRepository()
            ->save($sku)
        ;
        $result = $this->getRepository()->findOneByCode($code);
        $this->assertNotNull($result);
        $this->assertEquals($project, $result->getProject());
    }

    /**
     * Test the repository CRUD functions
     */
    public function testInsertFindDelete()
    {
        $code = uniqid();
        $project = uniqid();
        $sku = new Sku($code, $project);
        $this->getRepository()->save($sku);
        $result = $this->getRepository()->findOneByCode($code);
        $this->assertNotNull($result);
        $this->getRepository()->delete($sku);
        $result = $this->getRepository()->findOneByCode($code);
        $this->assertNull($result);
    }

    /**
     * Test the repository CRUD functions
     */
    public function testCountMatching()
    {
        $code = uniqid();
        $project = uniqid();
        $sku = new Sku($code, $project);
        $this->getRepository()->save($sku);
        $result = $this->getRepository()->countMatching(array(
            'code' => $code,
            'project' => $project
        ));
        $this->assertEquals(1, $result);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|SkuRepository
     */
    private function getRepository()
    {
        return $this
            ->em
            ->getRepository('MeupApiBundle:Sku')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
    }
}
