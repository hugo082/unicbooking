<?php

namespace Booking\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Service
 *
 * @ORM\Table(name="booking_service")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\ServiceRepository")
 */
class Service
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    public function __toString()
    {
        return $this->type . " - " . $this->name;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     * @Viewable(title="Type", index=1)
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function isAirport() {
        return $this->getTypeLower() == "airport";
    }

    public function isLimousine() {
        return $this->getTypeLower() == "limousine";
    }

    public function isTrain() {
        return $this->getTypeLower() == "train";
    }

    /**
     * Get type
     * @return string
     */
    public function getTypeLower()
    {
        return strtolower($this->type);
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Service
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @Viewable(title="Name", index=2)
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get gentella icon
     * @return string
     */
    public function getIconCode(): string {
        if ($this->isAirport())
            return "plane";
        if ($this->isLimousine())
            return "car";
        if ($this->isTrain())
            return "road";
    }
}

