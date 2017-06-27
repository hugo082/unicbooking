<?php

namespace Booking\AppBundle\Entity\Metadata;

use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Product as ProductType;
use Booking\AppBundle\Entity\Metadata\Service\Airport;
use Booking\AppBundle\Entity\Metadata\Service\Limousine;
use Booking\AppBundle\Entity\Metadata\Service\Train;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product Metadata
 *
 * @ORM\Table(name="booking_product_metadata")
 * @ORM\Entity()
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
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Metadata\Service\Limousine", columnPrefix="lim_")
     */
    private $limousine;

    /**
     * Airport Metadata
     * @var Airport
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Metadata\Service\Airport", columnPrefix="air_")
     */
    private $airport;
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

    public function isValid(): Bool {
        if (!$this->product_type)
            return false;
        $serviceTypeLower = $this->product_type->getService()->getTypeLower();
        if ($serviceTypeLower == "airport")
            return $this->airport->isValid();
        if ($serviceTypeLower == "limousine")
            return $this->limousine->isValid();
        if ($serviceTypeLower == "train")
            return $this->train->isValid();
        return false;
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
    public function setDate(\DateTime $date)
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
}

