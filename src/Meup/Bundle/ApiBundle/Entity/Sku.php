<?php
namespace Meup\Bundle\ApiBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Meup\Bundle\ApiBundle\Model\Sku as SkuModel;

/**
 * @author  c. Boissieux <christophe@1001pharmacies.com>
 *
 * Sku doctrine entity
 */
class Sku extends SkuModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Datetime
     */
    private $createdAt;

    /**
     * @var Datetime
     */
    private $deletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
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
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param Datetime $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return Datetime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set deletedAt
     *
     * @param Datetime $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(DateTime $deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return empty($this->deletedAt);
    }
}
