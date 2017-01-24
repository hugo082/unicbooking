<?php

namespace AppBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Book
*/
abstract class Book
{
    /**
     * @var mixed
     */
    protected $id;

    /**
    * @var string
    *
    * @ORM\Column(name="airport", type="string", length=255)
    */
    protected $airport;

    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date", type="date")
    * @Assert\Range(
    *      min = "now",
    *      minMessage = "date.min.now"
    * )
    */
    protected $date;

    /**
    * @var \Date
    *
    * @ORM\Column(name="creation_date", type="date")
    */
    protected $creationdate;

    /**
    * @var string
    *
    * @ORM\Column(name="service", type="string", length=255)
    */
    protected $service;

    /**
    * @var Product
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
    * @ORM\JoinColumn(nullable=false)
    */
    protected $product;

    /**
    * @var Flight
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Flight")
    * @ORM\JoinColumn(nullable=false)
    */
    protected $flight;

    /**
    * @var array
    * Adult Customer
    * @ORM\Column(name="adultcus", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    protected $adultcus;

    /**
    * @var array
    * Child Customer
    * @ORM\Column(name="childcus", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    protected $childcus;

    /**
    * @var string
    *
    * @ORM\Column(name="nameboard", type="string", length=255, nullable=true)
    */
    protected $nameboard;

    /**
    * @var int
    *
    * @ORM\Column(name="bags", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    protected $bags;

    /**
    * @var string
    *
    * @ORM\Column(name="agent_firstname", type="string", length=255)
    */
    protected $agentfirstname;

    /**
    * @var string
    *
    * @ORM\Column(name="agent_lastname", type="string", length=255)
    */
    protected $agentlastname;

    /**
    * @var string
    *
    * @ORM\Column(name="agent_email", type="string", length=255)
    */
    protected $agentemail;

    /**
    * @var int
    *
    * @ORM\Column(name="price", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    protected $price;

    /**
    * @var string
    *
    * @ORM\Column(name="timepu", type="string", length=255, nullable=true)
    */
    protected $timepu;

    /**
    * @var string
    *
    * @ORM\Column(name="addresspu", type="string", length=255, nullable=true)
    */
    protected $addresspu;

    /**
    * @var string
    *
    * @ORM\Column(name="addressdo", type="string", length=255, nullable=true)
    */
    protected $addressdo;

    /**
    * @var text
    * @ORM\Column(name="note", type="text", nullable=true)
    */
    protected $note;

    /**
    * @var string
    *
    * @ORM\Column(name="state", type="string", length=255, nullable=false)
    */
    protected $state;

    /**
    * Constructor
    */
    public function __construct()
    {
        $this->creationdate = new \DateTime();
        $this->state = "WAITING";
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
    * Set airport
    *
    * @param string $airport
    *
    * @return Book
    */
    public function setAirport($airport)
    {
        $this->airport = $airport;

        return $this;
    }

    /**
    * Get airport
    *
    * @return string
    */
    public function getAirport()
    {
        return $this->airport;
    }

    /**
    * Set date
    *
    * @param \DateTime $date
    *
    * @return Book
    */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
    * Get date
    *
    * @return \DateTime
    */
    public function getDate()
    {
        return $this->date;
    }

    /**
    * Set service
    *
    * @param string $service
    *
    * @return Book
    */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
    * Get service
    *
    * @return string
    */
    public function getService()
    {
        return $this->service;
    }

    /**
    * Set product
    *
    * @param string $product
    *
    * @return Book
    */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
    * Get product
    *
    * @return string
    */
    public function getProduct()
    {
        return $this->product;
    }

    /**
    * Set flight
    *
    * @param string $flight
    *
    * @return Book
    */
    public function setFlight($flight)
    {
        $this->flight = $flight;

        return $this;
    }

    /**
    * Get flight
    *
    * @return string
    */
    public function getFlight()
    {
        return $this->flight;
    }

    /**
    * Set nameboard
    *
    * @param string $nameboard
    *
    * @return Book
    */
    public function setNameboard($nameboard)
    {
        $this->nameboard = $nameboard;

        return $this;
    }

    /**
    * Get nameboard
    *
    * @return string
    */
    public function getNameboard()
    {
        return $this->nameboard;
    }

    /**
    * Set bags
    *
    * @param integer $bags
    *
    * @return Book
    */
    public function setBags($bags)
    {
        $this->bags = $bags;

        return $this;
    }

    /**
    * Get bags
    *
    * @return int
    */
    public function getBags()
    {
        return $this->bags;
    }

    /**
    * Set adultcus
    *
    * @param integer $adultcus
    *
    * @return Book
    */
    public function setAdultcus($adultcus)
    {
        $this->adultcus = $adultcus;

        return $this;
    }

    /**
    * Get adultcus
    *
    * @return integer
    */
    public function getAdultcus()
    {
        return $this->adultcus;
    }

    /**
    * Set childcus
    *
    * @param integer $childcus
    *
    * @return Book
    */
    public function setChildcus($childcus)
    {
        $this->childcus = $childcus;

        return $this;
    }

    /**
    * Get childcus
    *
    * @return integer
    */
    public function getChildcus()
    {
        return $this->childcus;
    }

    /**
    * Set user
    *
    * @param \AppBundle\Entity\User $user
    *
    * @return Book
    */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
    * Get user
    *
    * @return \AppBundle\Entity\User
    */
    public function getUser()
    {
        return $this->user;
    }

    /**
    * Set price
    *
    * @param integer $price
    *
    * @return Book
    */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
    * Get price
    *
    * @return integer
    */
    public function getPrice()
    {
        return $this->price;
    }

    /**
    * Set addresspu
    *
    * @param string $addresspu
    *
    * @return Book
    */
    public function setAddresspu($addresspu)
    {
        $this->addresspu = $addresspu;

        return $this;
    }

    /**
    * Get addresspu
    *
    * @return string
    */
    public function getAddresspu()
    {
        return $this->addresspu;
    }

    /**
    * Set addressdo
    *
    * @param string $addressdo
    *
    * @return Book
    */
    public function setAddressdo($addressdo)
    {
        $this->addressdo = $addressdo;

        return $this;
    }

    /**
    * Get addressdo
    *
    * @return string
    */
    public function getAddressdo()
    {
        return $this->addressdo;
    }

    /**
    * Set note
    *
    * @param string $note
    *
    * @return Book
    */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
    * Get note
    *
    * @return string
    */
    public function getNote()
    {
        return $this->note;
    }

    /**
    * Set timepu
    *
    * @param \DateTime $timepu
    *
    * @return Book
    */
    public function setTimepu($timepu)
    {
        $this->timepu = $timepu;

        return $this;
    }

    /**
    * Get timepu
    *
    * @return \DateTime
    */
    public function getTimepu()
    {
        return $this->timepu;
    }

    /**
    * Set creationdate
    *
    * @param \DateTime $creationdate
    *
    * @return Book
    */
    public function setCreationdate($creationdate)
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
    * Get creationdate
    *
    * @return \DateTime
    */
    public function getCreationdate()
    {
        return $this->creationdate;
    }

    /**
    * Set agentfirstname
    *
    * @param string $agentfirstname
    *
    * @return Book
    */
    public function setAgentfirstname($agentfirstname)
    {
        $this->agentfirstname = $agentfirstname;

        return $this;
    }

    /**
    * Get agentfirstname
    *
    * @return string
    */
    public function getAgentfirstname()
    {
        return $this->agentfirstname;
    }

    /**
    * Set agentlastname
    *
    * @param string $agentlastname
    *
    * @return Book
    */
    public function setAgentlastname($agentlastname)
    {
        $this->agentlastname = $agentlastname;

        return $this;
    }

    /**
    * Get agentlastname
    *
    * @return string
    */
    public function getAgentlastname()
    {
        return $this->agentlastname;
    }

    /**
    * Set agentemail
    *
    * @param string $agentemail
    *
    * @return Book
    */
    public function setAgentemail($agentemail)
    {
        $this->agentemail = $agentemail;

        return $this;
    }

    /**
    * Get agentemail
    *
    * @return string
    */
    public function getAgentemail()
    {
        return $this->agentemail;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Book
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
}
