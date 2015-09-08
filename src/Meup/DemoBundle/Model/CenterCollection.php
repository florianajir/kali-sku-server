<?php
/**
 * This file is part of the 1001 Pharmacies Symfony REST edition
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/symfony-bifrost-edition>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\DemoBundle\Model;

class CenterCollection
{
    /**
     * @var Center[]
     */
    public $centers;

    /**
     * @var integer
     */
    public $offset;

    /**
     * @var integer
     */
    public $limit;

    /**
     * @param Center[]  $conventioncenter
     * @param integer $offset
     * @param integer $limit
     */
    public function __construct($centers = array(), $offset = null, $limit = null)
    {
        $this->centers = $centers;
        $this->offset = $offset;
        $this->limit = $limit;
    }
}
