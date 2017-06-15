<?php

namespace Booking\AppBundle\Entity\Services;

use Booking\AppBundle\Entity\Flight;
use Doctrine\ORM\Mapping as ORM;

/**
 * Airport Service
 *
 * @ORM\Table(name="booking_service_airport")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\ServiceRepository")
 */
class Airport extends Basic
{
    /**
     * Default Flight
     * @var Flight
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Flight", cascade={"persist"})
     */
    private $flight;

    /**
     * Transit Flight
     * @var Flight
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Flight", cascade={"persist"})
     */
    private $flight_transit;


    /**
     * @return Flight
     */
    public function getFlight()
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
    public function getFlightTransit()
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

