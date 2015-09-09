<?php
namespace Meup\Bundle\ApiBundle\Service;

/**
 * Interface SkuCodeGeneratorInterface
 *
 * @author florianajir <florian@1001pharmacies.com
 */
interface SkuCodeGeneratorInterface
{
    /**
     * Generate a random sku code
     *
     * @return string
     */
    public function generateSkuCode();

    /**
     * Overwrite default sku code length
     *
     * @param int $codeLength
     */
    public function setCodeLength($codeLength);

    /**
     * Overwrite default sku code alphabet
     *
     * @param string $alphabet
     */
    public function setAlphabet($alphabet);
}
