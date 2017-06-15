<?php

namespace Booking\AppBundle\Entity\InternationalCodes;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * AirlinesCodes
 *
 * @ORM\Embeddable
 */
class AirlinesCodes extends InternationalCodes
{
    const CODES_FILE = "Data/airlinesCodes.min.json";
    const IATA_CODE_LEN = 2;
    const ICAO_CODE_LEN = 3;

    /**
     * @var string
     * @ORM\Column(name="number", type="string", length=5)
     */
    private $number;

    /**
     * Get iata by default, icao else.
     * @return string
     */
    public function getCode()
    {
        $airline_code = parent::getCode();
        return $airline_code . $this->number;
    }

    public function setCode($code, $force = false)
    {
        $len = (is_numeric($code{self::ICAO_CODE_LEN - 1})) ? self::IATA_CODE_LEN : self::ICAO_CODE_LEN;
        $this->number = substr($code, $len);
        $airline_code = substr($code, 0, $len);
        if ($airline_code != $this->_code || $force) {
            $this->_code = $airline_code;
            $this->computeCodes(true);
        }
    }

    public function getFullIcao() {
        return $this->icao . $this->number;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }
}