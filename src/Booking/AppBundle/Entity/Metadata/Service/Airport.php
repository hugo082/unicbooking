<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Booking\AppBundle\Entity\Flight;
use Doctrine\ORM\Mapping as ORM;

/**
 * Airport Service
 *
 * @ORM\Embeddable
 */
class Airport
{
    /**
     * Default Flight
     * @var Flight
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Flight", cascade={"persist"})
     * @ORM\JoinColumn(name="flight_id", referencedColumnName="id", nullable=true)
     */
    private $flight;

    /**
     * Transit Flight
     * @var Flight
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Flight", cascade={"persist"})
     * @ORM\JoinColumn(name="flight_tra_id", referencedColumnName="id", nullable=true)
     */
    private $flight_transit;

    public function isValid(): Bool {
        return $this->flight != null;
    }

    /**
     * @return Flight
     */
    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    /**
     * @param Flight $flight
     */
    public function setFlight(Flight $flight)
    {
        $this->flight = $flight;
    }

    /**
     * @return Flight
     */
    public function getFlightTransit(): ?Flight
    {
        return $this->flight_transit;
    }

    /**
     * @param Flight $flight_transit
     */
    public function setFlightTransit(Flight $flight_transit)
    {
        $this->flight_transit = $flight_transit;
    }
}

