<?php
namespace Meup\Bundle\ApiBundle\Service;

/**
 * Class SkuGenerator
 *
 * @author florianajir <florian@1001pharmacies.com
 */
class SkuGenerator implements SkuCodeGeneratorInterface
{
    /**
     * @var int
     */
    private $codeLength;

    /**
     * @param int $codeLength
     */
    public function __construct($codeLength)
    {
        $this->codeLength = $codeLength;
    }

    /**
     * {@inheritdoc}
     */
    public function generateSkuCode()
    {
        $char = "abcdefghijklmnopqrstuvwxyz0123456789";
        $char = str_shuffle($char);
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
}
