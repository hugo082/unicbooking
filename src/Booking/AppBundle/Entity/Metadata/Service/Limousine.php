<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Booking\AppBundle\Entity\Car;
use Doctrine\ORM\Mapping as ORM;

/**
 * Limousine Service
 *
 * @ORM\Table(name="booking_limousine_service_metadata")
 * @ORM\Entity()
 */
class Limousine implements iService
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
     * @ORM\Column(name="time", type="string")
     */
    protected $time;

    /**
     * @var string
     * @ORM\Column(name="pick_up", type="string", length=255, nullable=true)
     */
    private $pick_up;

    /**
     * @var string
     * @ORM\Column(name="drop_off", type="string", length=255, nullable=true)
     */
    private $drop_off;

    /**
     * @var array
     * @ORM\Column(name="additional_stops", type="array", nullable=true)
     */
    private $additional_stops;

    /**
     * @var string
     * @ORM\Column(name="start_mileage", type="string", length=255, nullable=true)
     */
    private $startMileage;

    public function isValid(): Bool {
        return $this->drop_off && $this->pick_up;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPickUp(): ?string
    {
        return $this->pick_up;
    }

    /**
     * @param string $pick_up
     */
    public function setPickUp(string $pick_up)
    {
        $this->pick_up = $pick_up;
    }

    /**
     * @return string
     */
    public function getDropOff(): ?string
    {
        return $this->drop_off;
    }

    /**
     * @param string $drop_off
     */
    public function setDropOff(string $drop_off)
    {
        $this->drop_off = $drop_off;
    }

    /**
     * @return string
     */
    public function getTime(): ?string
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime(string $time)
    {
        $this->time = $time;
    }

    public function getInformation(): string {
        return $this->pick_up;
    }

    /**
     * @return array
     */
    public function getAdditionalStops(): ?array
    {
        return $this->additional_stops;
    }

    /**
     * @param array $additional_stops
     */
    public function setAdditionalStops(array $additional_stops)
    {
        $this->additional_stops = $additional_stops;
    }

    /**
     * @return string
     */
    public function getStartMileage(): ?string
    {
        return $this->startMileage;
    }

    /**
     * @param string $startMileage
     */
    public function setStartMileage(string $startMileage)
    {
        $this->startMileage = $startMileage;
    }

    public function addAdditionalStop(string $name) {
        if ($this->additional_stops === null)
            $this->additional_stops = [];
        $this->additional_stops[] = $name;
    }
}

