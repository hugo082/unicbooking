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
    public const SERVICE_DEP = "DEP";
    public const SERVICE_ARR = "ARR";
    public const SERVICE_TRA = "TRA";

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Doctrine\IdGenerator")
     */
    private $id;

    /**
     * @var Airport
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airport", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $airport;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\GreaterThanOrEqual("yesterday")
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
     * @ORM\Column(name="service", type="string", length=255, nullable=false)
     */
    protected $service;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $product;

    /**
     * Default Flight
     * @var Flight
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Flight", cascade={"persist"})
     */
    private $flight;

    /**
     * Flight destination for transit service
     * @var Flight
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Flight", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $flighttransit;

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
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="books", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="archived", type="boolean")
     */
    protected $archived;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $driver;

    /**
     * @var Employee
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $greeter;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="book", cascade={"persist"})
     */
    protected $customers;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SubBook", mappedBy="parent", cascade={"remove", "persist"})
     */
    protected $subbooks;

    // /**
    // * @var Compagny
    // * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
    // * @ORM\JoinColumn(nullable=false)
    // */
    // private $compagny;

    /**
     * Constructor
     */
    public function __construct()
    {
        $date = new \DateTime();
        // $this->id = dechex($date->format('ymdHHis') . rand(0,99));
        $this->creationdate = $date;
        $this->state = "WAITING";
        $this->customers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subbooks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enabled = false;
        $this->archived = false;
    }

    public function getColor() {
        if ($this->user->getCompagny() == NULL) {
            return '#ff0000';
        } else {
            return $this->user->getCompagny()->getColor();
        }
    }

    /**
     * Compute price of this book. Without edit price.
     */
    public function getOptions() {
        $res = array();
        if ($this->product->getTransport()) $res[] = 'Airport transfer included';
        $res[] = $this->getFullBags();
        return $res;
    }

    /**
     * Compute price of this book. Without edit price.
     */
    public function updatePrice($doc = NULL) {
        $price = 0;
        $price += $this->product->getPrice();
        $price += $this->getAdditionalcus() * $this->product->getAdditionalPrice();
        $price += $this->getAdditionalCar() * 90;
        $cmp = $this->user->getCompagny();
        $price += $this->bags * $this->getPorterageprice();
        if ($doc != NULL) $price += 25 * count($doc->getRepository('AppBundle:SubBook')->getChargedCountEdit($this));
        $this->price = $price;
    }

    public function getDeviceConvertion(){
        $cmp = $this->user->getCompagny();
        if ($cmp && stripos($cmp->getName(), 'qatar') !== false) {
            return "QAR " . number_format($this->price * 3.8164, 2, ',', ' ');
        }
        return "USD " . number_format($this->price * 1.069, 2, ',', ' ');
    }

    public function getFullProductPrice() {
        return $this->product->getPrice() + $this->getAdditionalcus() * $this->product->getAdditionalPrice();
    }

    public function getPorterageprice() {
        $cmp = $this->user->getCompagny();
        return $cmp ? $cmp->getPortageprice() : 8;
    }

    /**
     * Get the full service of this book (ARR | DEP | TRA)
     * @return string
     */
    public function getFullServiceName() {
        if ($this->service == "TRA")
            return "book.form.trans";
        else
            return $this->flight->getFullType();
    }

    /**
     * Add $value at book.price
     */
    public function addToPrice($value) {
        $this->price += $value;
        return $this;
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
     * @return bool
     */
    public function isDeparture()
    {
        return $this->service == self::SERVICE_DEP;
    }

    /**
     * @return bool
     */
    public function isArrival()
    {
        return $this->service == self::SERVICE_ARR;
    }

    /**
     * @return bool
     */
    public function isTransit()
    {
        return $this->service == self::SERVICE_TRA;
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
     * Get total customers
     *
     * @return integer
     */
    public function getTotalcus()
    {
        return $this->childcus + $this->adultcus;
    }

    /**
     * Get total customers
     *
     * @return integer
     */
    public function getAdditionalcus()
    {
        $n = $this->getTotalcus() - $this->product->getPassengers();
        return ($n >= 0) ? $n : 0;
    }

    /**
     * Get total customers
     *
     * @return integer
     */
    public function getAdditionalCar()
    {
        if ($this->product->getTransport()) {
            return ceil($this->getTotalcus() / 4) - 1;
        }
        return 0;
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
     * @return integer
     */
    public function getBags()
    {
        return $this->bags;
    }

    /**
     * Get bags informations
     *
     * @return string
     */
    public function getFullBags()
    {
        $res = $this->bags . " bag";
        if ($this->bags > 1) $res .= "s";
        return $res . " (" . $this->bags * $this->getPorterageprice() . "€)";
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
     * Get full price
     *
     * @return string
     */
    public function getFullPrice()
    {
        return "EUR " . $this->price;
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
     * Get full state
     *
     * @return string
     */
    public function getFullState()
    {
        if ($this->state == "ACCEPTED") return "Confirmed";
        if ($this->state == "REJECTED") return "Cancelled";
        if ($this->state == "WAITING") return "Waitlist";
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Book
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Book
     */
    public function setProduct(\AppBundle\Entity\Product $product)
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
     * Get product quantity
     *
     * @return integer
     */
    public function getProductQuantity()
    {
        return "1 ( +" . $this->getAdditionalcus() . " PAX )";
    }

    /**
     * Set flight
     *
     * @param \AppBundle\Entity\Flight $flight
     *
     * @return Book
     */
    public function setFlight(\AppBundle\Entity\Flight $flight)
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
     * Get Flight with Transit support
     *
     * @return string
     */
    public function getFlightNumber()
    {
        if ($this->service == "TRA")
            return $this->flight->getNumber() . " → " . $this->flighttransit->getNumber();
        return $this->flight->getNumber();
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
     * Get greeter
     *
     * @return \AppBundle\Entity\Employee
     */
    public function getGreeterPreview()
    {
        if ($this->greeter)
            return $this->greeter->getLastname();
        return " - ";
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
     * Get customers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * Get customers preview
     *
     * @return string
     */
    public function getCustomersPreview()
    {
        if (count($this->customers) == 0)
            return " - ";
        /** @var Customer $mainCust */
        $mainCust = $this->customers[0];
        return $mainCust->getTitle() . " " . $mainCust->getLastname();
    }

    /**
     * Set parent customers to this
     *
     * @return Book
     */
    public function setCustomersParent()
    {
        foreach ($this->customers as $c)
            $c->setBook($this);
        return $this;
    }

    /**
     * Add subbook
     *
     * @param \AppBundle\Entity\Customer $subbook
     *
     * @return Book
     */
    public function addSubbook(\AppBundle\Entity\Customer $subbook)
    {
        $this->subbooks[] = $subbook;

        return $this;
    }

    /**
     * Remove subbook
     *
     * @param \AppBundle\Entity\Customer $subbook
     */
    public function removeSubbook(\AppBundle\Entity\Customer $subbook)
    {
        $this->subbooks->removeElement($subbook);
    }

    /**
     * Get subbooks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubbooks()
    {
        return $this->subbooks;
    }

    /**
     * Get subbooks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChargedSubbooks()
    {
        $subs = array();
        foreach ($this->subbooks as $sub) {
            if ($sub->getCharged()) $subs[] = $sub;
        }
        return $subs;
    }

    /**
     * Get subbooks
     *
     * @return Subbook
     */
    public function getLastSubbook()
    {
        $res = NULL;
        foreach ($this->subbooks as $sub) {
            if (!$res || $sub->getId() > $res->getId()) $res = $sub;
        }
        return $res;
    }

    /**
     * Get fullid
     *
     * @return integer
     */
    public function getFullid()
    {
        return $this->id . " - " . count($this->subbooks);
    }

    /**
     * Set airport
     *
     * @param \AppBundle\Entity\Airport $airport
     *
     * @return Book
     */
    public function setAirport(\AppBundle\Entity\Airport $airport = null)
    {
        $this->airport = $airport;

        return $this;
    }

    /**
     * Get airport
     *
     * @return \AppBundle\Entity\Airport
     */
    public function getAirport()
    {
        return $this->airport;
    }


    /**
     * Set flighttransit
     *
     * @param \AppBundle\Entity\Flight $flighttransit
     *
     * @return Book
     */
    public function setflighttransit($flighttransit)
    {
        $this->flighttransit = $flighttransit;

        return $this;
    }

    /**
     * Get flighttransit
     *
     * @return \AppBundle\Entity\Flight
     */
    public function getflighttransit()
    {
        return $this->flighttransit;
    }

    /**
     * get Destination Preview
     *
     * @return string
     */
    public function getDestinationPreview()
    {
        if ($this->service != "TRA")
            return $this->flight->getDestination();
        return $this->flight->getDestination() . " - " . $this->flighttransit->getDestination();
    }

    /**
     * get Location Preview
     *
     * @return string
     */
    public function getLocationPreview()
    {
        return $this->flight->getMainAirport()->getCode();
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     *
     * @return Book
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return boolean
     */
    public function getArchived()
    {
        return $this->archived;
    }
}
