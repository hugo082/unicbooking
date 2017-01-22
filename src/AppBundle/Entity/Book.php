<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Book
*
* @ORM\Table(name="book")
* @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
*/
class Book
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
    * @var string
    *
    * @ORM\Column(name="airport", type="string", length=255)
    */
    private $airport;

    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date", type="date")
    * @Assert\Range(
    *      min = "now",
    *      minMessage = "date.min.now"
    * )
    */
    private $date;

    /**
    * @var \Date
    *
    * @ORM\Column(name="creation_date", type="date")
    */
    private $creationdate;

    /**
    * @var string
    *
    * @ORM\Column(name="service", type="string", length=255)
    */
    private $service;

    /**
    * @var Product
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
    * @ORM\JoinColumn(nullable=false)
    */
    private $product;

    /**
    * @var Flight
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Flight")
    * @ORM\JoinColumn(nullable=false)
    */
    private $flight;

    /**
    * @var array
    * Adult Customer
    * @ORM\Column(name="adultcus", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    private $adultcus;

    /**
    * @var array
    * Child Customer
    * @ORM\Column(name="childcus", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    private $childcus;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="book", cascade={"persist"})
    */
    protected $customers;

    /**
    * @var string
    *
    * @ORM\Column(name="nameboard", type="string", length=255, nullable=true)
    */
    private $nameboard;

    /**
    * @var int
    *
    * @ORM\Column(name="bags", type="integer")
    * @Assert\Range(
    *      min = 0
    * )
    */
    private $bags;

    /**
    * @var User
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="books", cascade={"remove"})
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;

    /**
    * @var string
    *
    * @ORM\Column(name="agent_firstname", type="string", length=255)
    */
    private $agentfirstname;

    /**
    * @var string
    *
    * @ORM\Column(name="agent_lastname", type="string", length=255)
    */
    private $agentlastname;

    /**
    * @var string
    *
    * @ORM\Column(name="agent_email", type="string", length=255)
    */
    private $agentemail;

    /**
    * @var int
    *
    * @ORM\Column(name="price", type="integer")
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
    * @var string
    *
    * @ORM\Column(name="state", type="string", length=255, nullable=false)
    */
    private $state;

    /**
    * @var User
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
    * @ORM\JoinColumn(nullable=true)
    */
    private $driver;

    /**
    * @var User
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
    * @ORM\JoinColumn(nullable=true)
    */
    private $greeter;

    /**
    * @var text
    * @ORM\Column(name="note", type="text", nullable=true)
    */
    private $note;


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
    * Set customers
    *
    * @param array $customers
    *
    * @return Book
    */
    public function setCustomers($customers)
    {
        $this->customers = $customers;

        return $this;
    }

    /**
    * Get customers
    *
    * @return array
    */
    public function getCustomers()
    {
        return $this->customers;
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
    * Constructor
    */
    public function __construct()
    {
        $this->customers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
    * Add customer
    *
    * @param \AppBundle\Entity\Customer $customer
    *
    * @return Book
    */
    public function addCustomer(\AppBundle\Entity\Customer $customer)
    {
        $this->customers[] = $customer;

        return $this;
    }

    /**
    * Remove customer
    *
    * @param \AppBundle\Entity\Customer $customer
    */
    public function removeCustomer(\AppBundle\Entity\Customer $customer)
    {
        $this->customers->removeElement($customer);
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

    /**
     * Set driver
     *
     * @param \AppBundle\Entity\Employee $driver
     *
     * @return Book
     */
    public function setDriver(\AppBundle\Entity\Employee $driver = null)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getDriver()
    {
        return $this->driver;
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
     * Set greeter
     *
     * @param \AppBundle\Entity\Employee $greeter
     *
     * @return Book
     */
    public function setGreeter(\AppBundle\Entity\Employee $greeter = null)
    {
        $this->greeter = $greeter;

        return $this;
    }

    /**
     * Get greeter
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getGreeter()
    {
        return $this->greeter;
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
}
