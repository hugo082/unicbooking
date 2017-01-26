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
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    private $id;

    /**
    * @var int
    *
    * @ORM\Column(name="number", type="integer")
    */
    private $number;

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
    * @var Compagny
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
    * @ORM\JoinColumn(nullable=true)
    */
    private $compagny;

    /**
    * Get Full Name
    */
    public function getFullName()
    {
        $depAir = ($this->type == "DEP") ? "CDG" : "DOH";
        $arrAir = ($this->type == "ARR") ? "CDG" : "DOH";
        return $this->deptime->format('H:i') . " (" . $depAir . ") → " . $this->arrtime->format('H:i') . " (" . $arrAir . ") QR " . $this->number;
    }


    /**
    * Get id
    *
    * @return int
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set number
    *
    * @param integer $number
    *
    * @return Flight
    */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
    * Get number with compagny id
    *
    * @return string
    */
    public function getFullNumber()
    {
        return "QR" . $this->number;
    }

    /**
    * Get number
    *
    * @return int
    */
    public function getNumber()
    {
        return $this->number;
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
        return $value;
    }

    /**
     * Set compagny
     *
     * @param \AppBundle\Entity\Compagny $compagny
     *
     * @return Flight
     */
    public function setCompagny(\AppBundle\Entity\Compagny $compagny = null)
    {
        $this->compagny = $compagny;

        return $this;
    }

    /**
     * Get compagny
     *
     * @return \AppBundle\Entity\Compagny
     */
    public function getCompagny()
    {
        return $this->compagny;
    }
}
