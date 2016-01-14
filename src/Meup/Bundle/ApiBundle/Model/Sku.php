<?php
/**
 * This file is part of the 1001 Pharmacies kali-server
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/kali-server>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Meup\Bundle\ApiBundle\Model;

/**
 * Abstract class Sku Model
 *
 * @author Florian Ajir <florian@1001pharmacies.com>
 * @author c. Boissieux <christophe@1001pharmacies.com>
 */
abstract class Sku implements SkuInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $project;

    /**
     * @var string
     */
    protected $foreignType;

    /**
     * @var string
     */
    protected $foreignId;

    /**
     * @var string
     */
    protected $permalink;

    /**
     * @param string $code
     * @param string $project
     */
    public function __construct($code, $project)
    {
        $this->code = $code;
        $this->project = $project;
    }

    /**
     * {@inheritDoc}
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritDoc}
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * {@inheritDoc}
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getForeignType()
    {
        return $this->foreignType;
    }

    /**
     * {@inheritDoc}
     */
    public function setForeignType($foreignType)
    {
        $this->foreignType = $foreignType;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getForeignId()
    {
        return $this->foreignId;
    }

    /**
     * {@inheritDoc}
     */
    public function setForeignId($foreignId)
    {
        $this->foreignId = $foreignId;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * {@inheritDoc}
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }
}
