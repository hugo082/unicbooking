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
     * Origin Airport
     * @var Airport
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Airport")
     */
    private $origin;

    /**
     * Destination Airport
     * @var Airport
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Airport")
     */
    private $destination;

    /**
     * @var \DateTime
     * @ORM\Column(name="dep_time", type="datetime")
     */
    protected $departure_time;

    /**
     * @var \DateTime
     * @ORM\Column(name="arr_time", type="datetime")
     */
    protected $arrival_time;

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

    /**
     * @return Airport
     */
    public function getOrigin(): ?Airport
    {
        return $this->origin;
    }

    /**
     * @param Airport $origin
     */
    public function setOrigin(Airport $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return Airport
     */
    public function getDestination(): ?Airport
    {
        return $this->destination;
    }

    /**
     * @return \DateTime
     */
    public function getArrivalTime(): ?\DateTime
    {
        return $this->arrival_time;
    }

    /**
     * @param mixed $arrival_time
     */
    public function setArrivalTime($arrival_time)
    {
        $this->arrival_time = $this->convertToDateTime($arrival_time);
    }

    /**
     * @return \DateTime
     */
    public function getDepartureTime(): ?\DateTime
    {
        return $this->departure_time;
    }

    /**
     * @param mixed $departure_time
     */
    public function setDepartureTime($departure_time)
    {
        $this->departure_time = $this->convertToDateTime($departure_time);
    }

    /**
     * @param Airport $destination
     */
    public function setDestination(Airport $destination)
    {
        $this->destination = $destination;
    }

    public function getPath(): string {
        return $this->origin->getCodes()->getCode() . " → " . $this->destination->getCodes()->getCode();
    }

    public function getTime(): string {
        return $this->departure_time->format('H:i') . " → " . $this->arrival_time->format('H:i');
    }

    /**
     * Convert mixed value to \DateTime
     * @param $value
     * @return \DateTime
     */
    private function convertToDateTime($value) {
        if (is_string($value)) {
            return date_create_from_format('H:i:s', $value);
        }
        if (is_int($value)) {
            $date = new \DateTime();
            $date->setTimestamp($value);
            return $date;
        }
        return $value;
    }
}

