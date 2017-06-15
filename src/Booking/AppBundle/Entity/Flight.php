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


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

