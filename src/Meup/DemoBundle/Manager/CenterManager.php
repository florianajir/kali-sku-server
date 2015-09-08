<?php
/**
 * This file is part of the 1001 Pharmacies Symfony REST edition
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/symfony-bifrost-edition>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\DemoBundle\Manager;

use Symfony\Component\Security\Core\Util\SecureRandomInterface;

class CenterManager
{
    /** @var array centers */
    protected $data = array();

    /**
     * @var \Symfony\Component\Security\Core\Util\SecureRandomInterface
     */
    protected $randomGenerator;

    /**
     * @var string
     */
    protected $cacheDir;

    public function __construct(SecureRandomInterface $randomGenerator, $cacheDir)
    {
        if (file_exists($cacheDir . '/sf_centers_data')) {
            $data = file_get_contents($cacheDir . '/sf_centers_data');
            $this->data = unserialize($data);
        }

        $this->randomGenerator = $randomGenerator;
        $this->cacheDir = $cacheDir;
    }

    private function flush()
    {
        file_put_contents($this->cacheDir . '/sf_centers_data', serialize($this->data));
    }

    public function count()
    {
        return count($this->data);
    }

    public function fetch($start = 0, $limit = 10)
    {
        return array_slice($this->data, $start, $limit, true);
    }

    public function get($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        return $this->data[$id];
    }

    public function set($center)
    {
        if (null === $center->id) {
            if (empty($this->data)) {
                $center->id = 0;
            } else {
                end($this->data);
                $center->id = key($this->data) + 1;
            }
        }

        if (null === $center->secret) {
            $center->secret = base64_encode($this->randomGenerator->nextBytes(64));
        }

        $this->data[$center->id] = $center;
        $this->flush();
    }

    public function remove($id)
    {
        if (!isset($this->data[$id])) {
            return false;
        }

        unset($this->data[$id]);
        $this->flush();

        return true;
    }
}
