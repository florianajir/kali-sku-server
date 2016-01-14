<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Tests\Form\Type;

use Meup\Bundle\ApiBundle\Entity\Sku;
use Meup\Bundle\ApiBundle\Factory\SkuFactory;
use Meup\Bundle\ApiBundle\Form\SkuType;
use Meup\Bundle\ApiBundle\Manager\SkuManagerInterface;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class SkuTypeTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 * @author Florian Ajir <florian@1001pharmacies.com>
 */
class SkuTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $code = uniqid();
        $formData = array(
            'project'   => 'testProject',
            'type'      => 'testType',
            'id'        => 'testId',
            'permalink' => 'http://test',
        );

        $type = new SkuType($this->getSkuFactoryMock(), $this->getSkuManagerMock());
        $instance = new Sku($code, $formData['project']);
        $form = $this->factory->create($type, $instance);

        $object = clone $instance;
        $object
            ->setforeignType('testType')
            ->setForeignId('testId')
            ->setPermalink('http://test')
        ;

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

        $this->assertEquals('sku', $type->getName());
    }

    public function testSubmitValidDataWithoutSkuInstance()
    {
        $code = uniqid();
        $formData = array(
            'project'   => 'testProject',
            'type'      => 'testType',
            'id'        => 'testId',
            'permalink' => 'http://test',
        );
        $instance = new Sku($code, $formData['project']);
        $factory = $this->getSkuFactoryMock();
        $factory
            ->expects($this->once())
            ->method('create')
            ->with($code, $formData['project'])
            ->willReturn($instance);
        $manager = $this->getSkuManagerMock();
        $manager
            ->expects($this->once())
            ->method('generateUniqueCode')
            ->willReturn($code);
        $type = new SkuType($factory, $manager);
        $form = $this->factory->create($type);

        $object = clone $instance;
        $object
            ->setforeignType($formData['type'])
            ->setForeignId($formData['id'])
            ->setPermalink($formData['permalink'])
        ;

        // submit the data to the form directly
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SkuFactory
     */
    private function getSkuFactoryMock()
    {
        $factory = $this
            ->getMockBuilder('Meup\Bundle\ApiBundle\Factory\SkuFactory')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $factory;
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
}
