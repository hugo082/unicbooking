<?php

namespace AppBundle\Entity\InternationalCodes;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Embeddable
 */
abstract class InternationalCodes
{
    protected const CODES_FILE = null;
    public const IATA_CODE_LEN = null;
    public const ICAO_CODE_LEN = null;

    /**
     * @var string
     * @ORM\Column(name="icao", type="string", length=10)
     */
    protected $icao;

    /**
     * @var string
     * @ORM\Column(name="iata", type="string", length=10, nullable=true)
     */
    protected $iata;

    /**
     * @var string|null
     */
    protected $_code = null;

    protected function  computeCodes(bool $force = false) {
        if ($this->_code == null || $this->icao != null || !$force)
            return;
        $data = $this->getCodesData();
        $this->setCodes($data["content"]);
    }

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
        $this->_code = $code;
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
     * @param bool $compute
     */
    public function setIcao(string $icao, bool $compute = false)
    {
        $this->icao = $icao;
        if ($compute) {
            $this->_code = $icao;
            $data = $this->getCodesData();
            $this->searhCodesInValues($data["content"]);
        }
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

    private function getCodesData() {
        $data_codes_str = file_get_contents(__DIR__ . "/" . static::CODES_FILE);
        return json_decode($data_codes_str, true);
    }

    protected function setCodes(array $data) {
        if ($this->searhCodesInKey($data))
            return;
        if ($this->searhCodesInValues($data))
            return;
        $this->icao = $this->_code;
    }

    private function searhCodesInKey(array $data) {
        if (strlen($this->_code) != static::IATA_CODE_LEN)
            return false;
        if (key_exists($this->_code, $data)) {
            self::setInternationalCodes($data[$this->_code]);
            return true;
        }
        return false;
    }

    private function searhCodesInValues(array $data, string $key = "icao") {
        if ($key == "icao" && strlen($this->_code) != static::ICAO_CODE_LEN)
            return false;
        foreach ($data as $_key => $codes_array) {
            if ($codes_array[$key] == $this->_code) {
                $this->setInternationalCodes($codes_array);
                return true;
            }
        }
        return false;
    }

    private function setInternationalCodes(array $array_codes) {
        $this->iata = $array_codes["iata"];
        $this->icao = $array_codes["icao"];
    }
}