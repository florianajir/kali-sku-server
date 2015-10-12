<?php
namespace Meup\Bundle\ApiBundle\Tests\Service;

use Meup\Bundle\ApiBundle\Entity\Sku;
use Meup\Bundle\ApiBundle\Service\SkuAllocator;
use PHPUnit_Framework_TestCase;

/**
 * Class SkuCodeGeneratorTest
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
class SkuAllocatorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test allocate method without unicity code conflict
     */
    public function testAllocated()
    {
        $project = "application";
        $code = "1234567";
        $sku = new Sku();
        $skuCodeGenerator = $this->getSkuCodeGeneratorMock($code);
        $skuFactory = $this->getSkuFactoryMock($sku);
        $skuManager = $this->getSkuManagerMock();
        $allocator = new SkuAllocator($skuFactory, $skuManager, $skuCodeGenerator);
        $result = $allocator->allocate($project);
        $this->assertNotNull($result);
        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $result);
        $this->assertNotNull($result->getProject());
        $this->assertEquals($project, $result->getProject());
        $this->assertNotNull($result->getCode());
        $this->assertEquals($code, $result->getCode());
    }

    /**
     * @param string $code
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSkuCodeGeneratorMock($code)
    {
        $skuCodeGenerator = $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Service\SkuCodeGeneratorInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $skuCodeGenerator
            ->expects($this->any())
            ->method('generateSkuCode')
            ->will($this->returnValue($code));

        return $skuCodeGenerator;
    }

    /**
     * @param string $sku
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSkuFactoryMock($sku)
    {
        $skuFactory = $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Factory\SkuFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $skuFactory
            ->expects($this->any())
            ->method('create')
            ->willReturn($sku);

        return $skuFactory;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getSkuManagerMock()
    {
        $skuManager = $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Manager\SkuManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $skuManager
            ->expects($this->any())
            ->method('exists')
            ->will($this->returnValue(false));
        $skuManager
            ->expects($this->any())
            ->method('persist')
            ->willReturn(
                $this
                    ->getMockBuilder('Meup\Bundle\ApiBundle\Entity\Sku')
                    ->disableOriginalConstructor()
                    ->getMock()
            );

        return $skuManager;
    }
}
