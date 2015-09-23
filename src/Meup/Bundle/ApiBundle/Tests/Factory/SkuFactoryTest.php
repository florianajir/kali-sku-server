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
use Meup\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class SkuFactoryTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class SkuFactoryTest extends BaseTestCase
{
    public function testCreateSkuObject()
    {
        $factory = new SkuFactory('Meup\Bundle\ApiBundle\Entity\Sku');

        $this->assertInstanceOf('Meup\Bundle\ApiBundle\Entity\Sku', $factory->create());
    }
}
