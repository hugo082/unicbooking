<?php

namespace Booking\AppBundle\Entity\Metadata;

use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Client;
use Booking\AppBundle\Entity\Customer;
use Booking\AppBundle\Entity\Location;
use Booking\AppBundle\Entity\Metadata\Service\iService;
use Booking\AppBundle\Entity\Product as ProductType;
use Booking\AppBundle\Entity\Metadata\Service\Airport;
use Booking\AppBundle\Entity\Metadata\Service\Limousine;
use Booking\AppBundle\Entity\Metadata\Service\Train;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product Metadata
 *
 * @ORM\Table(name="booking_product_metadata")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\Metadata\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * Train Metadata
     * @var Train
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Metadata\Service\Train", columnPrefix="tra_")
     */
    private $train;

    /**
     * Limousine Metadata
     * @var Limousine
     * @ORM\OneToOne(targetEntity="Booking\AppBundle\Entity\Metadata\Service\Limousine", cascade={"persist"})
     * @ORM\JoinColumn(name="limousine_serv_id", referencedColumnName="id", nullable=true)
     */
    private $limousine;

    /**
     * Airport Metadata
     * @var Airport
     * @ORM\OneToOne(targetEntity="Booking\AppBundle\Entity\Metadata\Service\Airport", cascade={"persist"})
     * @ORM\JoinColumn(name="airport_serv_id", referencedColumnName="id", nullable=true)
     */
    private $airport;

    /**
     * Execution
     * @var Execution
     * @ORM\OneToOne(targetEntity="Booking\AppBundle\Entity\Metadata\Execution", cascade={"persist"})
     * @ORM\JoinColumn(name="exec_id", referencedColumnName="id")
     */
    private $execution;

    /**
     * Location
     * @var Location
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Location", cascade={"persist"})
     * @ORM\JoinColumn(name="loca_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @var Customer[]
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Customer", mappedBy="product", cascade={"persist"})
     */
    protected $customers;

    /**
     * @var integer
     * @ORM\Column(name="baggages", type="integer")
     * @Assert\Range(min = 0)
     */
    protected $baggages;

    /**
     * @var Book
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Book", inversedBy="products")
     * @ORM\JoinColumn(name="book", referencedColumnName="id")
     */
    private $book;

    /**
     * @var ProductType
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Product")
     */
    private $product_type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    protected $note;

    public function __construct()
    {
        $this->execution = new Execution();
    }

    public function isValid(): Bool {
        if (!$this->product_type)
            return false;
        $serviceTypeLower = $this->product_type->getService()->getTypeLower();
        if ($serviceTypeLower != "airport")
            $this->airport = null;
        if ($serviceTypeLower != "limousine")
            $this->limousine = null;
        $object = $this->{$serviceTypeLower};
        return $object instanceof iService ? $object->isValid() : false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Airport
     */
    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    /**
     * @param Airport $airport
     */
    public function setAirport(Airport $airport)
    {
        $this->airport = $airport;
    }

    /**
     * @return Limousine
     */
    public function getLimousine(): ?Limousine
    {
        return $this->limousine;
    }

    /**
     * @param Limousine $limousine
     */
    public function setLimousine(Limousine $limousine)
    {
        $this->limousine = $limousine;
    }

    /**
     * @return Train
     */
    public function getTrain(): ?Train
    {
        return $this->train;
    }

    /**
     * @param Train $train
     */
    public function setTrain(Train $train)
    {
        $this->train = $train;
    }

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note)
    {
        $this->note = $note;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(?\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return ProductType
     */
    public function getProductType(): ?ProductType
    {
        return $this->product_type;
    }

    /**
     * @param ProductType $product_type
     */
    public function setProductType(ProductType $product_type)
    {
        $this->product_type = $product_type;
    }

    /**
     * @return Book
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(Book $book)
    {
        $this->book = $book;
    }

    /**
     * @return Execution
     */
    public function getExecution(): ?Execution
    {
        return $this->execution;
    }

    /**
     * @param Execution $execution
     */
    public function setExecution(Execution $execution)
    {
        $this->execution = $execution;
    }

    /**
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * @return Customer[]
     */
    public function getCustomers()
    {
        return $this->customers;
    }

    /**
     * @param Customer[] $customers
     */
    public function setCustomers($customers)
    {
        $this->customers = $customers;
    }

    public function getCustomersRecap(): string {
        $count = count($this->customers);
        if ($count == 0)
            return "Unknow";
        else
            return $this->customers[0] . " +(" . ($count - 1) . ")";
    }

    /**
     * @return int
     */
    public function getBaggages(): ?int
    {
        return $this->baggages;
    }

    /**
     * @param int $baggages
     */
    public function setBaggages(int $baggages)
    {
        $this->baggages = $baggages;
    }

    /**
     * @ORM\PrePersist
     */
    public function computeExecutionSteps() {
        if ($this->product_type->getService()->isAirport()) {
            $this->execution->setAirportDepartureSteps();
        } else if ($this->product_type->getService()->isLimousine()) {
            $this->execution->setLimousineSteps();
        } else if ($this->product_type->getService()->isTrain()) {
            $this->execution->setTrainSteps();
        } else
            $this->execution->setEmptySteps();
    }

    public function linkSubEntities() {
        foreach ($this->getCustomers() as $customer)
            $customer->setProduct($this);
    }

    public function getPrice(Client $client = null) {
        $base = $this->product_type->getPrice()->getTtc($client);
        if ($this->product_type->getService()->isLimousine())
            return $base + $this->limousine->getCar()->getPrice()->getCount();
        return $base;
    }

    public function getPriceCount() {
        $base = $this->product_type->getPrice()->getCount();
        if ($this->product_type->getService()->isLimousine())
            return $base . " + " . $this->limousine->getCar()->getPrice()->getCount();
        return $base;
    }
}

