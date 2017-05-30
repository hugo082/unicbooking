<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flight
 *
 * @ORM\Table(name="flight")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FlightRepository")
 */
class Flight
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * ICAO and IATA Codes
     * @var InternationalCodes
     * @ORM\Embedded(class="AppBundle\Entity\InternationalCodes", columnPrefix="codes_")
     */
    private $codes;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deptime", type="time")
     */
    private $deptime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrtime", type="time")
     */
    private $arrtime;

    /**
     * @var Airport
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport", cascade={"persist"})
     */
    private $depair;

    /**
     * @var Airport
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport", cascade={"persist"})
     */
    private $arrair;

    /**
     * @var Compagny
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
     * @ORM\JoinColumn(nullable=true)
     */
    private $compagny;

    public function __construct()
    {
        $this->codes = new InternationalCodes();
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Flight
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getFullType()
    {
        return ($this->type == "ARR") ? 'book.form.arr' : 'book.form.dep';
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getSelectionType() // NOT USED
    {
        return ($this->type == "ARR") ?
            '<span class="se-type back-cmpcolor">Arrival</span> Departure'
            : 'Arrival <span class="se-type back-cmpcolor">Departure</span>';
    }

    /**
     * Set deptime
     *
     * @param \DateTime $deptime
     *
     * @return Flight
     */
    public function setDeptime($deptime)
    {
        $this->deptime = $this->convertToDateTime($deptime);

        return $this;
    }

    /**
     * Get deptime
     *
     * @return \DateTime
     */
    public function getDeptime()
    {
        return $this->deptime;
    }

    /**
     * Set arrtime
     *
     * @param \DateTime $arrtime
     *
     * @return Flight
     */
    public function setArrtime($arrtime)
    {
        $this->arrtime = $this->convertToDateTime($arrtime);
        return $this;
    }

    /**
     * Get arrtime
     *
     * @return \DateTime
     */
    public function getArrtime()
    {
        return $this->arrtime;
    }

    private function convertToDateTime($value) {
        if (is_string($value)) {
            return date_create_from_format('H:i:s', $value);
        }
        if (is_int($value)) {
            $date = new \DateTime();
            $date->setTimestamp($value);
            return $date;
        }
        return $value;
    }

    /**
     * Set compagny
     *
     * @param Compagny $compagny
     *
     * @return Flight
     */
    public function setCompagny(Compagny $compagny = null)
    {
        $this->compagny = $compagny;

        return $this;
    }

    /**
     * Get compagny
     *
     * @return Compagny
     */
    public function getCompagny()
    {
        return $this->compagny;
    }

    /**
     * Set depair
     *
     * @param Airport $depair
     *
     * @return Flight
     */
    public function setDepair(Airport $depair)
    {
        $this->depair = $depair;

        return $this;
    }

    /**
     * Get depair
     *
     * @return \AppBundle\Entity\Airport
     */
    public function getDepair()
    {
        return $this->depair;
    }

    /**
     * Set arrair
     *
     * @param Airport $arrair
     *
     * @return Flight
     */
    public function setArrair(Airport $arrair)
    {
        $this->arrair = $arrair;

        return $this;
    }

    /**
     * Get arrair
     *
     * @return \AppBundle\Entity\Airport
     */
    public function getArrair()
    {
        return $this->arrair;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->depair->getCodes()->getCode() . " → " . $this->arrair->getCodes()->getCode();
    }

    /**
     * Get destination with time
     *
     * @return string
     */
    public function getDestinationWithTime()
    {
        if ($this->type == "ARR") {
            return $this->codes->getCode(). " " . $this->depair->getCodes()->getCode() . " → " . $this->arrair->getCodes()->getCode() . " (" . $this->arrtime->format('H:i') . ")";
        }
        return $this->codes->getCode(). " " . $this->depair->getCodes()->getCode() ." (" . $this->deptime->format('H:i') . ") → " . $this->arrair->getCodes()->getCode();
    }

    /**
     * Get destination with time
     *
     * @return string
     */
    public function getTime()
    {
        if ($this->type == "ARR") {
            return $this->arrtime->format('H:i');
        }
        return $this->deptime->format('H:i');
    }

    /**
     * Get Full Name
     */
    public function getFullName()
    {
        return $this->codes->getCode(). " " . $this->depair->getCodes()->getCode() ." (" . $this->deptime->format('H:i') . ") → " . $this->arrair->getCodes()->getCode() . " (" . $this->arrtime->format('H:i') . ")";
    }

    /**
     * Get the main airport where the service takes place
     */
    public function getMainAirport()
    {
        if ($this->type == "ARR") {
            return $this->arrair;
        }
        return $this->depair;
    }

    /**
     * @return InternationalCodes
     */
    public function getCodes(): ?InternationalCodes
    {
        return $this->codes;
    }

    /**
     * @param InternationalCodes $codes
     */
    public function setCodes(InternationalCodes $codes)
    {
        $this->codes = $codes;
    }
}
