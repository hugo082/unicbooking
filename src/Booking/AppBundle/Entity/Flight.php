<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\InternationalCodes\AirlinesCodes;
use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Table(name="booking_flight")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\FlightRepository")
 */
class Flight
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
     * ICAO and IATA Codes
     * @var AirlinesCodes
     * @ORM\Embedded(class="Booking\AppBundle\Entity\InternationalCodes\AirlinesCodes", columnPrefix="codes_")
     */
    private $codes;

    public function __construct()
    {
        $this->codes = new AirlinesCodes();
    }

    public function encode() {
        return array(
            "id" => $this->id,
            "codes" => $this->codes->encode(),
            //"origin_time" => $this->deptime->getTimestamp(),
            //"origin" => $this->depair->encode(),
            //"destination_time" => $this->arrtime->getTimestamp(),
            //"destination" => $this->arrair->encode()
        );
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
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return AirlinesCodes
     */
    public function getCodes(): ?AirlinesCodes
    {
        return $this->codes;
    }
}

