<?php

namespace Booking\AppBundle\Entity\Services;

use Booking\AppBundle\Entity\Car;
use Doctrine\ORM\Mapping as ORM;

/**
 * Limousine Service
 *
 * @ORM\Table(name="booking_service_limousine")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\ServiceRepository")
 */
class Limousine extends Basic
{
    /**
     * Default Flight
     * @var Car
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Car", cascade={"persist"})
     */
    private $car;

    /**
     * @var string
     * @ORM\Column(name="pick_up", type="string", length=255)
     */
    private $pick_up;

    /**
     * @var string
     * @ORM\Column(name="drop_off", type="string", length=255)
     */
    private $drop_off;

    /**
     * @return string
     */
    public function getPickUp(): string
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
    public function getDropOff(): string
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
}

