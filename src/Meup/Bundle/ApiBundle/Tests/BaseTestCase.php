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

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class BaseTestCase
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectRepository
     */
    protected function getObjectRepositoryMock()
    {
        $repository =  $this
            ->getMockBuilder('Doctrine\Common\Persistence\ObjectRepository')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $repository;
    }

    /**
     * @param $repository
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    protected function getObjectManagerMock($repository, $class)
    {
        $om = $this
            ->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->getMockForAbstractClass()
        ;

        $om
            ->expects($this->once())
            ->method('getRepository')
            ->with($class)
            ->willReturn($repository)
        ;

        return $om;
    }
}