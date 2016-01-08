<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Tests\Factory;

use Meup\Bundle\ApiBundle\Factory\SkuFactory;
use Meup\Bundle\ApiBundle\Service\SkuAllocatorInterface;

/**
 * Class SkuFactoryTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 * @author Florian Ajir <florian@1001pharmacies.com>
 */
class SkuFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test new sku instance creation from factory::create method
     */
    public function testCreateSkuObject()
    {
        $project = 'test';
        $code = uniqid();
        $factory = new SkuFactory('Meup\Bundle\ApiBundle\Entity\Sku');
        $skuInstance = $factory->create($code, $project);
        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $skuInstance);
        $this->assertEquals($project, $skuInstance->getProject());
        $this->assertEquals($code, $skuInstance->getCode());
        $this->assertNull($skuInstance->getForeignId());
        $this->assertNull($skuInstance->getForeignType());
        $this->assertNull($skuInstance->getPermalink());
    }
}
