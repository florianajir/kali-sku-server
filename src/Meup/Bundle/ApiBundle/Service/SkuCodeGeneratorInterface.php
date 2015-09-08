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
     * @return string
     */
    public function generateSkuCode();

    /**
     * @param int $codeLength
     */
    public function setCodeLength($codeLength);
}
