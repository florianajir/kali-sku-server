<?php

namespace Meup\Bundle\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use Meup\Bundle\ApiBundle\Model\Identifier;

/**
 * Sku
 */
class Sku implements  Identifier
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var datetime
     */
    private $createdAt;

    /**
     * @var datetime
     */
    private $deletedAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string
     * @return Sku
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
    * Set createdAt
    *
    * @param datetime $createdAt
    * @return Sku
    */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return datetime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedAt
     *
     * @param datetime $deletedAt
     * @return Sku
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

}
