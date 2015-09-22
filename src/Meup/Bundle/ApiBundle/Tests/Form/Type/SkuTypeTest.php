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
use Meup\Bundle\ApiBundle\Form\Type\SkuType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class SkuTypeTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class SkuTypeTest  extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'project'   => 'testProject',
            'type'      => 'testType',
            'id'        => 'testId',
            'permalink' => 'http://test',
        );

        $type = new SkuType();
        $form = $this->factory->create($type);

        $object = new Sku();
        $object
            ->setProject('testProject')
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
}
