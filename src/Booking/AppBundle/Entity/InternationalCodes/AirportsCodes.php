<?php

namespace Booking\AppBundle\Entity\InternationalCodes;

use Doctrine\ORM\Mapping as ORM;

/**
 * AirportsCodes
 *
 * @ORM\Embeddable
 */
class AirportsCodes extends InternationalCodes
{
    const CODES_FILE = "Data/airportsCodes.min.json";
    const IATA_CODE_LEN = 3;
    const ICAO_CODE_LEN = 4;
}