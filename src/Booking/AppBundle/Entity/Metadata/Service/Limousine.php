<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Booking\AppBundle\Entity\Car;
use Doctrine\ORM\Mapping as ORM;

/**
 * Limousine Service
 *
 * @ORM\Embeddable
 */
class Limousine
{
    /**
     * Default Flight
     * @var Car
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Car", cascade={"persist"})
     * @ORM\JoinColumn(name="car", referencedColumnName="id", nullable=true)
     */
    private $car;

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

    public function isValid(): Bool {
        return $this->car && $this->drop_off && $this->pick_up;
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
     * @return Car
     */
    public function getCar(): ?Car
    {
        return $this->car;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
    }
}

