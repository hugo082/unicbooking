<?php

namespace AppBundle\Checker;

use AppBundle\Entity\Flight;
use AppBundle\Entity\InternationalCodes;

class InternationalCodesChecker
{
    private const AIRLINES_CODES_FILE = "Data/airlinesCodes.min.json";
    private const AIRPORTS_CODES_FILE = "Data/airportsCodes.min.json";

    public static function  computeFlightCodes(Flight $fligth) {
        $data_codes_str = file_get_contents(self::AIRLINES_CODES_FILE);
        $data = json_decode($data_codes_str, true);
        $unknowTypeCode = substr($fligth->getCodes()->getCode(), 0, 2);
        self::setCodes($fligth->getCodes(), $data, 2);
    }

    private static function setCodes(InternationalCodes $codes, array $data, int $iata_code_length) {
        $iata_codeode = substr($codes->getCode(), 0, $iata_code_length);
        if (self::searhCodesInKey($iata_codeode, $codes, $data))
            return;
        $icao_code = substr($codes->getCode(), 0, $iata_code_length + 1);
        if (self::searhCodesInValues($icao_code, $codes, $data))
            return;
        $codes->setIcao($codes->getCode());
    }

    private static function searhCodesInKey(string $code, InternationalCodes $codes, array $data) {
        if (key_exists($code, $data)) {
            self::computeInternationalCodes($codes, $data[$code]);
            return true;
        }
        return false;
    }

    private static function searhCodesInValues(string $code, InternationalCodes $codes, array $data, string $key = "icao") {
        foreach ($data as $_key => $codes_array) {
            if ($codes_array[$key] == $code) {
                self::computeInternationalCodes($codes, $codes_array);
                return true;
            }
        }
        return false;
    }

    private static function computeInternationalCodes(InternationalCodes $codes, array $array_codes) {
        $codes->setIata($array_codes["iata"]);
        $codes->setIcao($array_codes["icao"]);
    }

}