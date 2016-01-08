<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Service;

/**
 * Provide an interface for code generation
 *
 * @author Florian Ajir <florian@1001pharmacies.com
 */
interface CodeGeneratorInterface
{
    /**
     * Generate a random sku code
     *
     * @return string
     */
    public function generateSkuCode();
}
