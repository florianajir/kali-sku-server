<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Tests\Manager;

use Meup\Bundle\ApiBundle\Entity\Sku;
use Meup\Bundle\ApiBundle\Manager\SkuManager;
use Meup\Bundle\ApiBundle\Tests\DoctrineTestCase;

/**
 * Class SkuManagerTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 * @author Florian Ajir <florian@1001pharmacies.com>
 */
class SkuManagerTest extends DoctrineTestCase
{
    public function testFind()
    {
        $code = uniqid();
        $sku = $this->getSkuMock();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('findOneByCode')
            ->with($code)
            ->willReturn($sku)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->find($code);
        $this->assertEquals($sku, $result);
    }

    public function testFindOneByProjectTypeId()
    {
        $project = uniqid();
        $objectType = uniqid();
        $objectId = uniqid();
        $sku = $this->getSkuMock();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('findOneByProjectTypeId')
            ->with($project, $objectType, $objectId)
            ->willReturn($sku)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->findByProjectTypeId($project, $objectType, $objectId);
        $this->assertEquals($sku, $result);
    }

    public function testExists()
    {
        $code = uniqid();
        $sku = $this->getSkuMock();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('findOneByCode')
            ->with($code)
            ->willReturn($sku)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->exists($code);
        $this->assertInternalType('bool', $result);
        $this->assertTrue($result);
    }

    public function testNotExists()
    {
        $code = uniqid();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('findOneByCode')
            ->with($code)
            ->willReturn(null)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->exists($code);
        $this->assertInternalType('bool', $result);
        $this->assertFalse($result);
    }

    public function testSave()
    {
        $sku = $this->getSkuMock();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('save')
            ->with($sku)
            ->willReturn($sku)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->save($sku);
        $this->assertEquals($sku, $result);
    }

    public function testDelete()
    {
        $sku = $this->getSkuMock();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('delete')
            ->with($sku)
            ->willReturn($sku)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $manager->delete($sku);
    }

    public function testDisable()
    {
        // Don't using mock because of condition instance of Sku
        $sku = new Sku(uniqid(), uniqid());
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('save')
            ->with($sku)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->disable($sku);
        $this->assertFalse($result->isActive());
    }

    public function testCount()
    {
        $count = 10;
        $criteria = array();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('countMatching')
            ->with($criteria)
            ->will($this->returnValue($count))
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $result = $manager->count($criteria);
        $this->assertInternalType('int', $result);
        $this->assertEquals($count, $result);
    }

    public function testDeleteWhere()
    {
        $criteria = array();
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('deleteWhere')
            ->with($criteria)
        ;
        $manager = new SkuManager($repository, $this->getSkuFactoryMock(), $this->getCodeGeneratorMock());
        $manager->deleteWhere($criteria);
    }

    /**
     * Test allocate method without unicity code conflict
     */
    public function testAllocate()
    {
        $project = uniqid();
        $code = uniqid();
        $codeGenerator = $this->getCodeGeneratorMock();
        $codeGenerator
            ->expects($this->any())
            ->method('generateSkuCode')
            ->will($this->returnValue($code));

        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('save')
        ;

        $sku = new Sku($code, $project);
        $factory = $this->getSkuFactoryMock();
        $factory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($sku));

        $manager = new SkuManager($repository, $factory, $codeGenerator);
        $result = $manager->allocate($project);
        $this->assertNotNull($result);
        $this->assertInstanceOf(get_class($sku), $result);
        $this->assertNotNull($result->getProject());
        $this->assertEquals($project, $result->getProject());
        $this->assertNotNull($result->getCode());
        $this->assertEquals($code, $result->getCode());
    }

    /**
     *
     */
    public function testGetUniqueCode()
    {
        $code = uniqid();
        $codeGenerator = $this->getCodeGeneratorMock();
        $codeGenerator
            ->expects($this->once())
            ->method('generateSkuCode')
            ->will($this->returnValue($code));
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->once())
            ->method('findOneByCode')
            ->willReturn(null);
        ;
        $factory = $this->getSkuFactoryMock();
        $manager = new SkuManager($repository, $factory, $codeGenerator);
        $result = $manager->generateUniqueCode();
        $this->assertNotNull($result);
        $this->assertEquals($code, $result);
    }

    /**
     *
     */
    public function testFailedGetUniqueCode()
    {
        $codeGenerator = $this->getCodeGeneratorMock();
        $codeGenerator
            ->expects($this->any())
            ->method('generateSkuCode')
            ->will($this->returnValue(uniqid()))
        ;
        $repository = $this->getSkuRepositoryMock();
        $repository
            ->expects($this->exactly(SkuManager::MAX_GENERATION_ATTEMPTS))
            ->method('findOneByCode')
            ->will($this->returnValue(uniqid()));
        ;
        $factory = $this->getSkuFactoryMock();
        $manager = new SkuManager($repository, $factory, $codeGenerator);
        $this->setExpectedException('DomainException');
        $manager->generateUniqueCode();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Meup\Bundle\ApiBundle\Repository\SkuRepositoryInterface
     */
    private function getSkuRepositoryMock()
    {
        return $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Repository\SkuRepositoryInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Meup\Bundle\ApiBundle\Model\SkuInterface
     */
    private function getSkuMock()
    {
        return $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Model\SkuInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Meup\Bundle\ApiBundle\Factory\SkuFactory
     */
    private function getSkuFactoryMock()
    {
        return $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Factory\SkuFactory')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Meup\Bundle\ApiBundle\Service\CodeGeneratorInterface
     */
    private function getCodeGeneratorMock()
    {
        return $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Service\CodeGeneratorInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
