<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Tests\Command;

use Meup\Bundle\ApiBundle\Command\PurgeCommand;
use Meup\Bundle\ApiBundle\Manager\SkuManagerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class PurgeCommandTest
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 */
class PurgeCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $application = new Application();
        $manager = $this->getSkuManagerMock();
        $manager
            ->expects($this->once())
            ->method('deleteWhere')
        ;
        $manager
            ->expects($this->once())
            ->method('count')
            ->willReturn(1)
        ;
        $application->add(new PurgeCommand($manager));
        $command = $application->find('meup:kali:purge');
        $commandTester = new CommandTester($command);
        $project = uniqid();

        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => $project,
            '--force' => true
        ));
        $this->assertRegExp('/1 sku found/', $commandTester->getDisplay());
    }


    public function testExecuteWithDialog()
    {
        $application = new Application();
        $manager = $this->getSkuManagerMock();
        $manager
            ->expects($this->once())
            ->method('deleteWhere')
        ;
        $manager
            ->expects($this->once())
            ->method('count')
            ->willReturn(1)
        ;
        $application->add(new PurgeCommand($manager));
        $command = $application->find('meup:kali:purge');
        $dialog = $this->getDialogHelperMock();
        $dialog->expects($this->at(0))
            ->method('askConfirmation')
            ->will($this->returnValue(true)); // The user confirms

        // We override the standard helper with our mock
        $command->getHelperSet()->set($dialog, 'dialog');
        $commandTester = new CommandTester($command);
        $project = uniqid();

        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => $project
        ));
        $this->assertRegExp('/1 sku found/', $commandTester->getDisplay());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SkuManagerInterface
     */
    private function getSkuManagerMock()
    {
        $manager = $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Manager\SkuManagerInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $manager;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\Console\Helper\DialogHelper
     */
    private function getDialogHelperMock()
    {
        return $this->getMock(
            'Symfony\Component\Console\Helper\DialogHelper',
            array('askConfirmation')
        );
    }
}
