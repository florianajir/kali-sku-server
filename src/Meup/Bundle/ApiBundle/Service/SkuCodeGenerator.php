<?php
namespace Meup\Bundle\ApiBundle\Service;

/**
 * Class SkuGenerator
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
class SkuGenerator implements SkuCodeGeneratorInterface
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

    /**
     * {@inheritdoc}
     */
    public function setCodeLength($codeLength)
    {
        $this->codeLength = $codeLength;
    }

    /**
     * {@inheritdoc}
     */
    public function setAlphabet($alphabet)
    {
        $this->alphabet = $alphabet;
    }
}
