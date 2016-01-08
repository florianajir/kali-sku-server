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

use Meup\Bundle\ApiBundle\Service\CodeGenerator;

/**
 * Class CodeGeneratorTest
 *
 * @author Florian Ajir <florian@1001pharmacies.com>
 * @author Loïc AMBROSINI <loic@1001pharmacies.com>
 */
class CodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateSkuCode()
    {
        $generator = new CodeGenerator('7', 'abcdefghijklmnopqrstuvwxyz01234565789');
        $sku = $generator->generateSkuCode();

        $this->assertNotNull($sku);
    }

    public function testCodeLength()
    {
        $generator = new CodeGenerator('3', '123');
        $sku = $generator->generateSkuCode();

        $this->assertEquals('3', strlen($sku));
    }

    public function testAlphabet()
    {
        $generator = new CodeGenerator('7', 'a');
        $sku = $generator->generateSkuCode();

        $this->assertEquals('aaaaaaa', $sku);
    }
}
