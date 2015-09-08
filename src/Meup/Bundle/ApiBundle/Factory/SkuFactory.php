<?php
namespace Meup\Bundle\ApiBundle\Factory;

use ReflectionClass;
use Meup\Bundle\ApiBundle\Model\Sku;

/**
 * Class SkuFactory
 *
 * @author florianajir <florian@1001pharmacies.com>
 */
class SkuFactory
{
    /**
     * @var ReflectionClass
     */
    protected $class;

    /**
     * @param string $classname
     */
    public function __construct($classname)
    {
        $this->class = new ReflectionClass($classname);
    }

    /**
     * @return Sku
     */
    public function create()
    {
        return $this->class->newInstance();
    }
}