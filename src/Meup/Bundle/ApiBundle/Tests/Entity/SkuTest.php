<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Entity;
use Meup\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class SkuTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class SkuTest extends BaseTestCase
{
    public function testConstructor()
    {
        $Sku = new Sku();
        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $Sku);
    }

    public function testAccessors()
    {
        $now = new \DateTime('now');

        $Sku = new Sku();
        $Sku
            ->setId(1)
            ->setCreatedAt($now)
            ->setDeletedAt($now)
            ->setCode('0123456789')
            ->setForeignId('0123456789')
            ->setforeignType('type')
            ->setPermalink('http://')
            ->setProject('project')
        ;

        $this->assertEquals(1, $Sku->getId());
        $this->assertEquals($now, $Sku->getCreatedAt());
        $this->assertEquals($now, $Sku->getDeletedAt());
        $this->assertEquals('0123456789', $Sku->getCode());
        $this->assertEquals('0123456789', $Sku->getForeignId());
        $this->assertEquals('type', $Sku->getForeignType());
        $this->assertEquals('http://', $Sku->getPermalink());
        $this->assertEquals('project', $Sku->getProject());
        $this->assertFalse($Sku->isActive());


    }
}
