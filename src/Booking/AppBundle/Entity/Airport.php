<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\InternationalCodes\AirportsCodes;
use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

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

    public function __construct()
    {
        $this->codes = new AirportsCodes();
    }

    /**
     * Get id
     * @Viewable(title="id", index=0)
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
     * @Viewable(title="Name", index=1)
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @Viewable(title="Codes", index=2)
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
     * @Viewable(title="Supported", index=3)
     * @return bool
     */
    public function isSupported()
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

