<?php

namespace AppBundle\Entity\InternationalCodes;

use Doctrine\ORM\Mapping as ORM;

/**
 * AirportsCodes
 *
 * @ORM\Embeddable
 */
class AirportsCodes extends InternationalCodes
{
    protected const CODES_FILE = "Data/airportsCodes.min.json";
    public const IATA_CODE_LEN = 3;
    public const ICAO_CODE_LEN = 4;
}