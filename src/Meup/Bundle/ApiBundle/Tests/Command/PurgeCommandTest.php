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
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class PurgeCommandTest
 *
 * @author Florian AJIR <florian@1001pharmacies.com>
 */
class PurgeCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new PurgeCommand());

        $command = $application->find('meup:kali:purge');
//        $commandTester =
            new CommandTester($command);
        //TODO solve need a database connexion
//        $commandTester->execute(array(
//            'command' => $command->getName(),
//            'project' => 'kali',
//            'type' => 'sku',
//        ));
    }
}
