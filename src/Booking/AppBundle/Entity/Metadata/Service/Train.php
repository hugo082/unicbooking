<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Doctrine\ORM\Mapping as ORM;

/**
 * Train Service
 *
 * @ORM\Embeddable
 */
class Train
{
    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="station", type="string", length=255, nullable=true)
     */
    protected $station;

    /**
     * @var string
     * @ORM\Column(name="time", type="string", nullable=true)
     */
    protected $time;

    public function isValid(): Bool {
        return $this->code && $this->station && $this->time;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getStation(): ?string
    {
        return $this->station;
    }

    /**
     * @param string $station
     */
    public function setStation(string $station)
    {
        $this->station = $station;
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
}
