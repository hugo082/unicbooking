<?php

namespace AppBundle\Entity;

use AppBundle\Entity\InternationalCodes\AirportsCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * Airport
 *
 * @ORM\Table(name="airport")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AirportRepository")
 */
class Airport
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="removed", type="boolean")
     */
    private $removed = false;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * ICAO and IATA Codes
     * @var AirportsCodes
     * @ORM\Embedded(class="AppBundle\Entity\InternationalCodes\AirportsCodes", columnPrefix="codes_")
     */
    private $codes;

    /**
     * @var boolean
     *
     * @ORM\Column(name="selectable", type="boolean")
     */
    private $selectable;

    /**
     * @var Compagny
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
     * @ORM\JoinColumn(nullable=true)
     */
    private $compagny;

    public function __construct()
    {
        $this->codes = new AirportsCodes();
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
     * Set name
     *
     * @param string $name
     *
     * @return Airport
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->codes->getCode() . " - " . $this->name;
    }

    /**
     * Set compagny
     *
     * @param \AppBundle\Entity\Compagny $compagny
     *
     * @return Airport
     */
    public function setCompagny(\AppBundle\Entity\Compagny $compagny = null)
    {
        $this->compagny = $compagny;

        return $this;
    }

    /**
     * Get compagny
     *
     * @return \AppBundle\Entity\Compagny
     */
    public function getCompagny()
    {
        return $this->compagny;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     *
     * @return Airport
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * Set selectable
     *
     * @param boolean $selectable
     *
     * @return Airport
     */
    public function setSelectable($selectable)
    {
        $this->selectable = $selectable;

        return $this;
    }

    /**
     * Get selectable
     *
     * @return boolean
     */
    public function getSelectable()
    {
        return $this->selectable;
    }

    /**
     * @return AirportsCodes
     */
    public function getCodes(): ?AirportsCodes
    {
        return $this->codes;
    }

    /**
     * @param AirportsCodes $codes
     */
    public function setCodes(AirportsCodes $codes)
    {
        $this->codes = $codes;
    }
}
