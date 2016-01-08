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

/**
 * Class SkuTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 * @author Florian Ajir <florian@1001pharmacies.com>
 */
class SkuTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $sku = new Sku(uniqid(), uniqid());
        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $sku);
    }

    public function testAccessors()
    {
        $code = uniqid();
        $project = uniqid();
        $now = new \DateTime('now');
        $sku = new Sku($code, $project);
        $sku
            ->setId(1)
            ->setCreatedAt($now)
            ->setDeletedAt($now)
            ->setForeignId('0123456789')
            ->setforeignType('type')
            ->setPermalink('http://')
        ;

        $this->assertEquals(1, $sku->getId());
        $this->assertEquals($now, $sku->getCreatedAt());
        $this->assertEquals($now, $sku->getDeletedAt());
        $this->assertEquals($code, $sku->getCode());
        $this->assertEquals($project, $sku->getProject());
        $this->assertEquals('0123456789', $sku->getForeignId());
        $this->assertEquals('type', $sku->getForeignType());
        $this->assertEquals('http://', $sku->getPermalink());
    }
    public function testDesactive()
    {
        $code = uniqid();
        $project = uniqid();
        $sku = new Sku($code, $project);
        $this->assertTrue($sku->isActive());
        $now = new \DateTime('now');
        $sku->setDeletedAt($now);
        $this->assertFalse($sku->isActive());
    }
}
