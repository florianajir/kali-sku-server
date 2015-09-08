<?php
namespace Meup\Bundle\ApiBundle\Factory;

use Meup\Bundle\ApiBundle\Model\SkuInterface;
use ReflectionClass;

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
     * @return SkuInterface
     */
    public function create()
    {
        return $this->class->newInstance();
    }
}
