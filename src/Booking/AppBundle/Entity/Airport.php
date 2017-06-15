<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\InternationalCodes\AirportsCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * Airport
 *
 * @ORM\Table(name="booking_airport")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\AirportRepository")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * ICAO and IATA Codes
     * @var AirportsCodes
     * @ORM\Embedded(class="Booking\AppBundle\Entity\InternationalCodes\AirportsCodes", columnPrefix="codes_")
     */
    private $codes;

    /**
     * @var boolean
     * @ORM\Column(name="supported", type="boolean")
     */
    private $supported;


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
     * @return AirportsCodes
     */
    public function getCodes()
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

    /**
     * @return bool
     */
    public function isSupported(): bool
    {
        return $this->supported;
    }

    /**
     * @param bool $supported
     */
    public function setSupported(bool $supported)
    {
        $this->supported = $supported;
    }
}

