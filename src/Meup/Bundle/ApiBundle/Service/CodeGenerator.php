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
 * Provide code generation for a given alphabet and length parameters
 *
 * @author Florian Ajir <florian@1001pharmacies.com>
 */
class CodeGenerator implements CodeGeneratorInterface
{
    /**
     * @var int
     */
    private $codeLength;

    /**
     * @var string
     */
    private $alphabet;

    /**
     * @param int    $codeLength
     * @param string $alphabet
     */
    public function __construct($codeLength, $alphabet)
    {
        $this->codeLength = $codeLength;
        $this->alphabet = $alphabet;
    }

    /**
     * {@inheritdoc}
     */
    public function generateSkuCode()
    {
        $char = str_shuffle($this->alphabet);
        for ($i = 0, $rand = '', $l = strlen($char) - 1; $i < $this->codeLength; $i++) {
            $rand .= $char{mt_rand(0, $l)};
        }

        return $rand;
    }
}
