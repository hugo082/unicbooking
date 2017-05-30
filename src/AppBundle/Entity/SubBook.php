<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Book
 *
 * @ORM\Table(name="subbook")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubBookRepository")
 */
class SubBook
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
     * @ORM\Column(name="airport", type="string", length=255, nullable=true)
     */
    private $airport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     * @Assert\Range(
     *      min = "now",
     *      minMessage = "date.min.now"
     * )
     */
    private $date;

    /**
     * @var \Date
     *
     * @ORM\Column(name="creation_date", type="date", nullable=true)
     */
    private $creationdate;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=255, nullable=true)
     */
    private $service;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=true)
     */
    private $product;

    /**
     * Default Flight
     * @var Flight
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Flight", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $flight;

    /**
     * Flight destination for transit service
     * @var Flight
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Flight", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $flightTransit;

    /**
     * @var array
     * Adult Customer
     * @ORM\Column(name="adultcus", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0
     * )
     */
    private $adultcus;

    /**
     * @var array
     * Child Customer
     * @ORM\Column(name="childcus", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0
     * )
     */
    private $childcus;

    /**
     * @var string
     *
     * @ORM\Column(name="nameboard", type="string", length=255, nullable=true)
     */
    private $nameboard;

    /**
     * @var int
     *
     * @ORM\Column(name="bags", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0
     * )
     */
    private $bags;

    /**
     * @var string
     *
     * @ORM\Column(name="agent_firstname", type="string", length=255, nullable=true)
     */
    private $agentfirstname;

    /**
     * @var string
     *
     * @ORM\Column(name="agent_lastname", type="string", length=255, nullable=true)
     */
    private $agentlastname;

    /**
     * @var string
     *
     * @ORM\Column(name="agent_email", type="string", length=255, nullable=true)
     */
    private $agentemail;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0
     * )
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="timepu", type="string", length=255, nullable=true)
     */
    private $timepu;

    /**
     * @var string
     *
     * @ORM\Column(name="addresspu", type="string", length=255, nullable=true)
     */
    private $addresspu;

    /**
     * @var string
     *
     * @ORM\Column(name="addressdo", type="string", length=255, nullable=true)
     */
    private $addressdo;

    /**
     * @var text
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @var Book
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book", inversedBy="subbooks", cascade={"remove", "persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="charged", type="boolean")
     */
    private $charged;

    /**
     * Constructor
     */
    public function __construct($book, $changeset)
    {
        $this->charged = false;
        $this->creationdate = new \DateTime();
        $this->state = "WAITING";
        $this->parent = $book;
        $this->number = count($book->getSubbooks()) + 1;
        foreach ($changeset as $key => $values) {
            $this->$key = $values[0];
        }
    }

    public function backChange($book){
        $rejected_var = array('parent', 'state', 'creationdate', 'id', 'number');
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value) {
            if ($value != NULL && !in_array($key, $rejected_var)) {
                $method = "set" . ucfirst($key);
                $book->$method($value);
            }
        }
        return $book;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Book $parent
     *
     * @return SubBook
     */
    public function setParent(\AppBundle\Entity\Book $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Book
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return SubBook
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
     * @return SubBook
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
     * Set creationdate
     *
     * @param \DateTime $creationdate
     *
     * @return SubBook
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
     * Set service
     *
     * @param string $service
     *
     * @return SubBook
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
     * Set adultcus
     *
     * @param integer $adultcus
     *
     * @return SubBook
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
     * @return SubBook
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
     * Set nameboard
     *
     * @param string $nameboard
     *
     * @return SubBook
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
     * @return SubBook
     */
    public function setBags($bags)
    {
        $this->bags = $bags;

        return $this;
    }

    /**
     * Get bags
     *
     * @return integer
     */
    public function getBags()
    {
        return $this->bags;
    }

    /**
     * Set agentfirstname
     *
     * @param string $agentfirstname
     *
     * @return SubBook
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
     * @return SubBook
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
     * @return SubBook
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
     * Set price
     *
     * @param integer $price
     *
     * @return SubBook
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
     * Set timepu
     *
     * @param string $timepu
     *
     * @return SubBook
     */
    public function setTimepu($timepu)
    {
        $this->timepu = $timepu;

        return $this;
    }

    /**
     * Get timepu
     *
     * @return string
     */
    public function getTimepu()
    {
        return $this->timepu;
    }

    /**
     * Set addresspu
     *
     * @param string $addresspu
     *
     * @return SubBook
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
     * @return SubBook
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
     * @return SubBook
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
     * Set state
     *
     * @param string $state
     *
     * @return SubBook
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

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return SubBook
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set flight
     *
     * @param \AppBundle\Entity\Flight $flight
     *
     * @return SubBook
     */
    public function setFlight(\AppBundle\Entity\Flight $flight = null)
    {
        $this->flight = $flight;

        return $this;
    }

    /**
     * Get flight
     *
     * @return \AppBundle\Entity\Flight
     */
    public function getFlight()
    {
        return $this->flight;
    }

    /**
     * Set charged
     *
     * @param boolean $charged
     *
     * @return SubBook
     */
    public function setCharged($charged)
    {
        $this->charged = $charged;

        return $this;
    }

    /**
     * Get charged
     *
     * @return boolean
     */
    public function getCharged()
    {
        return $this->charged;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return SubBook
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set flightTransit
     *
     * @param string $flightTransit
     *
     * @return SubBook
     */
    public function setFlightTransit($flightTransit)
    {
        $this->flightTransit = $flightTransit;

        return $this;
    }

    /**
     * Get flightTransit
     *
     * @return string
     */
    public function getFlightTransit()
    {
        return $this->flightTransit;
    }
}
