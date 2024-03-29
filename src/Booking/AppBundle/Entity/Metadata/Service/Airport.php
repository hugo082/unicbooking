<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Booking\ApiBundle\Exception\UnsupportedAirportException;
use Booking\AppBundle\Entity\Flight;
use Booking\AppBundle\Repository\FlightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Airport Service
 *
 * @ORM\Table(name="booking_airport_service_metadata")
 * @ORM\Entity()
 */
class Airport implements iService
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    public function computeFlightWithCurrentFlight(FlightRepository $repo) {
        $flight = $this->getFlightWithFlight($this->flight, $repo);
        if ($flight instanceof Flight)
            $this->setFlight($flight);
    }
    public function computeFlightTransitWithCurrentFlight(FlightRepository $repo) {
        $flight = $this->getFlightWithFlight($this->flight_transit, $repo);
        if ($flight instanceof Flight)
            $this->setFlightTransit($flight);
    }

    /**
     * @param Flight|null $flight
     * @param FlightRepository $repo
     * @return null|Flight
     */
    private function getFlightWithFlight($flight, FlightRepository $repo) {
        if (!$flight || !$flight->getId())
            return null;
        $flight = $repo->find($flight->getId());
        if ($flight instanceof Flight)
            return $flight;
        return null;
    }

    public function getDestination(): string {
        if ($this->flight_transit == null)
            return $this->flight->getPath();
        return $this->flight->getPath() . " - " . $this->flight_transit->getPath();
    }

    public function getCode(): string {
        if ($this->flight_transit == null)
            return $this->flight->getCodes()->getCode();
        return $this->flight->getCodes()->getCode() . " → " . $this->flight_transit->getCodes()->getCode();
    }

    public function getTime(): string {
        if ($this->flight_transit == null)
            return $this->flight->getComputedTime();
        return $this->flight->getComputedTime() . " - " . $this->flight_transit->getComputedTime();
    }

    public function checkAirportsSupport() {
        if ($this->flight != null) {
            $dest = $this->flight->getDestination();
            if ($this->flight_transit != null) {
                if (!$dest->isSupported())
                    throw new UnsupportedAirportException(UnsupportedAirportException::TYPE_AIRPORT, $dest);
                if ($this->flight_transit->getOrigin()->getCodes()->getIcao() != $dest->getCodes()->getIcao())
                    throw new UnsupportedAirportException(UnsupportedAirportException::TYPE_CONNECTION);
            } else {
                if (!$dest->isSupported() && !$this->flight->getOrigin()->isSupported()) {
                    $airport = $dest->isSupported() ? $this->flight->getOrigin() : $dest;
                    throw new UnsupportedAirportException(UnsupportedAirportException::TYPE_AIRPORT, $airport);
                }
            }
        }
    }

    public function getInformation(): string {
        if ($this->flight_transit == null)
            return $this->flight->getInformation();
        return $this->flight->getInformation() . " - " . $this->flight_transit->getCodes()->getCode();
    }
}

