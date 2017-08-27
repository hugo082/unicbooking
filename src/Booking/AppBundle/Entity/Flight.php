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
    public const TYPE_DEP = ["Departure", "DEP"];
    public const TYPE_ARR = ["Arrival", "ARR"];

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
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Airport", cascade={"persist", "refresh"})
     */
    private $origin;

    /**
     * Destination Airport
     * @var Airport
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Airport", cascade={"persist", "refresh"})
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

    /**
     * @var string
     * @ORM\Column(name="type", type="string")
     */
    protected $type;

    public function __construct()
    {
        $this->type = self::TYPE_DEP[1];
        $this->codes = new AirlinesCodes();
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

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->origin->getCodes()->getCode() . " → " . $this->destination->getCodes()->getCode();
    }

    /**
     * @return string
     */
    public function getTime(): string {
        return $this->departure_time->format('H:i') . " → " . $this->arrival_time->format('H:i');
    }

    /**
     * @return string
     */
    public function getComputedTime(): string {
        return $this->departure_time->format('H:i');
    }

    /**
     * @return string
     */
    public function getInformation(): string {
        return $this->origin->getCodes()->getCode() . " " . $this->codes->getCode();
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type)
    {
        if ($type != self::TYPE_DEP[1] && $type != self::TYPE_ARR[1])
            return;
        $this->type = $type;
    }

    public function isDeparture(): bool {
        return $this->type === self::TYPE_DEP[1];
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

