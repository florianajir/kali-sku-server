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

class Center
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $secret;

    /**
     * @var string The center name
     */
    public $name;

    /**
     * @var string The original version
     */
    public $version = 1;

    /**
     * @var string This version will be used since 1.1
     */
    public $new_version = 1.1;

    /**
     * String representation for a center
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    public function getAssociatedEventsRel()
    {
        return 'associated_events';
    }

    public function getAssociatedEvents()
    {
        return array(
            new Event('Comic Con', new \DateTime('October 23, 2015')),
            new Event('Japan Expo', new \DateTime('July 02, 2015')),
        );
    }
}
