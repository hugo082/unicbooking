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
     * @var boolean
     *
     * @ORM\Column(name="removed", type="boolean")
     */
    private $removed = false;

    /**
    * @var string
    *
    * @ORM\Column(name="number", type="string", length=255)
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
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport")
    * @ORM\JoinColumn(nullable=false)
    */
    private $depair;

    /**
    * @var Compagny
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport")
    * @ORM\JoinColumn(nullable=false)
    */
    private $arrair;

    /**
    * @var Compagny
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
    * @ORM\JoinColumn(nullable=true)
    */
    private $compagny;

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

    /**
    * Set depair
    *
    * @param \AppBundle\Entity\Airport $depair
    *
    * @return Flight
    */
    public function setDepair(\AppBundle\Entity\Airport $depair)
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
    * @param \AppBundle\Entity\Airport $arrair
    *
    * @return Flight
    */
    public function setArrair(\AppBundle\Entity\Airport $arrair)
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
    * Set number
    *
    * @param string $number
    *
    * @return Flight
    */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
    * Get number
    *
    * @return string
    */
    public function getNumber()
    {
        return $this->number;
    }

    /**
    * Get destination
    *
    * @return string
    */
    public function getDestination()
    {
        return $this->depair->getCode() . " → " . $this->arrair->getCode();
    }

    /**
    * Get destination with time
    *
    * @return string
    */
    public function getDestinationWithTime()
    {
        if ($this->type == "ARR") {
            return $this->number. " " . $this->depair->getCode() . " → " . $this->arrair->getCode() . " (" . $this->arrtime->format('H:i') . ")";
        }
        return $this->number. " " . $this->depair->getCode() ." (" . $this->deptime->format('H:i') . ") → " . $this->arrair->getCode();
    }

    /**
    * Get Full Name
    */
    public function getFullName()
    {
        return $this->number. " " . $this->depair->getCode() ." (" . $this->deptime->format('H:i') . ") → " . $this->arrair->getCode() . " (" . $this->arrtime->format('H:i') . ")";
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
     * Set removed
     *
     * @param boolean $removed
     *
     * @return Flight
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean
     */
    public function getRemoved()
    {
        return $this->removed;
    }
}
