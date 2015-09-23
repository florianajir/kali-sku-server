<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\Bundle\ApiBundle\Tests\Service;

use Meup\Bundle\ApiBundle\Service\SkuCodeGenerator;
use Meup\Bundle\ApiBundle\Tests\BaseTestCase;

/**
 * Class SkuCodeGeneratorTest
 *
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class SkuCodeGeneratorTest extends BaseTestCase
{
    public function testGenerateSkuCode()
    {
        $generator = new SkuCodeGenerator('7', 'abcdefghijklmnopqrstuvwxyz01234565789');
        $sku = $generator->generateSkuCode();

        $this->assertNotNull($sku);
    }

    public function testSetCodeLenght()
    {
        $generator = new SkuCodeGenerator('7', 'abcdefghijklmnopqrstuvwxyz01234565789');
        $generator->setCodeLength('6');
        $sku = $generator->generateSkuCode();

        $this->assertEquals('6', strlen($sku));
    }

    public function testSetAlphabet()
    {
        $generator = new SkuCodeGenerator('7', 'abcdefghijklmnopqrstuvwxyz01234565789');
        $generator->setAlphabet('a');
        $sku = $generator->generateSkuCode();

        $this->assertEquals('aaaaaaa', $sku);
    }
}
