<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Embeddable
 */
class InternationalCodes
{
    /**
     * @var string
     * @ORM\Column(name="icao", type="string", length=10, nullable=true)
     */
    private $icao;

    /**
     * @var string
     * @ORM\Column(name="iata", type="string", length=10, nullable=true)
     */
    private $iata;

    /**
     * Get iata by default, icao else.
     * @return string
     */
    public function getCode()
    {
        if ($this->iata != null)
            return $this->iata;
        return $this->icao;
    }

    public function setCode($code)
    {
        $this->icao = $code;
    }

    /**
     * @return string
     */
    public function getIcao()
    {
        return $this->icao;
    }

    /**
     * @param string $icao
     */
    public function setIcao(string $icao)
    {
        $this->icao = $icao;
    }

    /**
     * @return string
     */
    public function getIata()
    {
        return $this->iata;
    }

    /**
     * @param string $iata
     */
    public function setIata(string $iata)
    {
        $this->iata = $iata;
    }
}