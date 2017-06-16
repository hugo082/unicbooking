<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Booking\AppBundle\Entity\Flight;
use Doctrine\ORM\Mapping as ORM;

/**
 * Airport Service
 *
 * @ORM\Embeddable
 */
class Service
{
    /**
     * Train Metadata
     * @var Train
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Metadata\Service\Train", columnPrefix="tra_")
     */
    private $train;

    /**
     * Limousine Metadata
     * @var Limousine
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Metadata\Service\Limousine", columnPrefix="lim_")
     */
    private $limousine;

    /**
     * Airport Metadata
     * @var Airport
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Metadata\Service\Airport", columnPrefix="air_")
     */
    private $airport;

    /**
     * @var string
     * @ORM\Column(name="note", type="text")
     */
    protected $note;


    /**
     * @return Airport
     */
    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    /**
     * @return Limousine
     */
    public function getLimousine(): ?Limousine
    {
        return $this->limousine;
    }

    /**
     * @return Train
     */
    public function getTrain(): ?Train
    {
        return $this->train;
    }

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }
}

