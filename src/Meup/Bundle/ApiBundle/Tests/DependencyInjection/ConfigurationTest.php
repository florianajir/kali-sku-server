<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Tests\DependencyInjection;

use Meup\Bundle\ApiBundle\DependencyInjection\MeupApiExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Meup\Bundle\ApiBundle\DependencyInjection\Configuration;
use Meup\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class ConfigurationTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class ConfigurationTest extends BaseTestCase
{
    /**
     * @var MeupApiExtension
     */
    private $extension;
    /**
     * Root name of the configuration
     *
     * @var string
     */
    private $root;
    /**
     * @return MeupApiExtension
     */
    protected function getExtension()
    {
        return new MeupApiExtension();
    }
    /**
     * @return ContainerBuilder
     */
    private function getContainer()
    {
        $container = new ContainerBuilder();

        return $container;
    }
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();
        $this->extension = $this->getExtension();
        $this->root      = "meup_geo_location";
    }

    public function testGetConfigWithDefaultValues()
    {
        $this->extension->load(array(), $container = $this->getContainer());
    }

    public function testGetConfigurationTree()
    {
        $configuration = new Configuration();

        $this->assertInstanceOf(
            '\Symfony\Component\Config\Definition\Builder\TreeBuilder',
            $configuration->getConfigTreeBuilder()
        );
    }
}
