<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Factory;

use Meup\Bundle\ApiBundle\Model\SkuInterface;
use ReflectionClass;

/**
 * Class SkuFactory: provide a factory to create new Sku entities - instanciate with sku code and project attributes
 *
 * @author Florian Ajir <florian@1001pharmacies.com>
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
     * Create a new instance of Sku
     * just need project parameter, will automatically set a unique sku code
     *
     * @param string $code
     * @param string $project
     *
     * @return SkuInterface
     */
    public function create($code, $project)
    {
        return $this->class->newInstance($code, $project);
    }
}
