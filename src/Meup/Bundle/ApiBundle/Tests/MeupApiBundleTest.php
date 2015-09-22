<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Tests;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class MeupApiBundleTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class MeupApiBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $bundle = $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\MeupApiBundle')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;
        $container = new ContainerBuilder();
        $bundle->build($container);
    }
}