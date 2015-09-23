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

use Meup\Bundle\ApiBundle\Doctrine\SkuManager;
use Meup\Bundle\ApiBundle\Model\SkuInterface;
use Meup\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class SkuManagerTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class SkuManagerTest extends BaseTestCase
{
    public function testFindSkuByUniquesParameters()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(
                array(
                    'project'       => 'projectName',
                    'foreignType'   => 'objectType',
                    'foreignId'     => 'objectId'
                )
            )
            ->willReturn($this->getSkuModelMock())
        ;

        $om = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');

        $sku = $manager->findByUniqueGroup(
            'projectName',
            'objectType',
            'objectId'
        );

        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $sku);
    }

    public function testSkuAlreadyExists()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(
                array(
                    'code' => 'skuCode'
                )
            )
            ->willReturn($this->getSkuModelMock())
        ;

        $om = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');

        $sku = $manager->exists('skuCode');

        $this->assertTrue($sku);
    }

    public function testSkuNotExists()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(
                array(
                    'code' => 'skuCode'
                )
            )
            ->willReturn(null)
        ;

        $om = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');

        $sku = $manager->exists('skuCode');

        $this->assertFalse($sku);
    }

    public function testGetSkuBySkuIdentifier()
    {
        $repository = $this->getObjectRepositoryMock();
        $repository
            ->expects($this->any())
            ->method('findOneBy')
            ->with(
                array(
                    'code' => 'skuCode',
                )
            )
            ->willReturn($this->getSkuModelMock())
        ;

        $om = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');

        $sku = $manager->getByCode('skuCode');

        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $sku);
    }

    public function testPersistSku()
    {
        $sku = $this->getSkuModelMock();
        $repository = $this->getObjectRepositoryMock();
        $om         = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $om
            ->expects($this->once())
            ->method('flush')
        ;
        $manager    = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager->persist($sku, true);
    }

    public function testPersistSkuWithoutFlushingData()
    {
        $sku = $this->getSkuModelMock();
        $repository = $this->getObjectRepositoryMock();
        $om         = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager    = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager->persist($sku, false);
    }

    public function testDeleteSku()
    {
        $sku = $this->getSkuModelMock();
        $sku
            ->expects($this->any())
            ->method('isActive')
            ->willReturn(false)
        ;
        $repository = $this->getObjectRepositoryMock();
        $om         = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $om
            ->expects($this->once())
            ->method('flush')
        ;
        $manager    = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $updatedSku = $manager->delete($sku, true);

        $this->assertFalse($sku->isActive());
    }

    public function testDeleteSkuWithoutFlushingData()
    {
        $sku = $this->getSkuModelMock();
        $sku
            ->expects($this->any())
            ->method('isActive')
            ->willReturn(true)
        ;
        $repository = $this->getObjectRepositoryMock();
        $om         = $this->getObjectManagerMock($repository, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $manager    = new SkuManager($om, 'Meup\Bundle\ApiBundle\Entity\Sku');
        $updatedSku = $manager->delete($sku, false);

        $this->assertTrue($sku->isActive());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SkuInterface
     */
    private function getSkuModelMock()
    {
        return  $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Entity\Sku')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
