<?php

namespace Booking\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Booking\AppBundle\Entity\Metadata\Product as ProductMet;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Book
 *
 * @ORM\Table(name="booking_book")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\BookRepository")
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
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="date")
     */
    protected $creation_date;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Metadata\Product", mappedBy="book", cascade={"persist"})
     */
    protected $products;

    /**
     * @var \DateTime
     */
    private $first_date;
    /**
     * @var \DateTime
     */
    private $last_date;

    public function __construct()
    {
        $this->creation_date = new \DateTime();
        $this->products = new ArrayCollection();
    }

    public function isValid(): Bool {
        /** @var \Booking\AppBundle\Entity\Metadata\Product $product */
        foreach ($this->getProducts() as $product) {
            if (!$product->isValid())
                return false;
        }
        return true;
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
     * @return ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts(ArrayCollection $products)
    {
        $this->products = $products;
    }

    public function linkProducts() {
        /** @var ProductMet $prod */
        foreach ($this->getProducts() as $prod)
            $prod->setBook($this);
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creation_date;
    }

    public function getFirstDate(): ?\DateTime {
        if ($this->first_date == null)
            $this->computeIntervalDates();
        return $this->first_date;
    }

    public function getLastDate(): ?\DateTime {
        if ($this->last_date == null)
            $this->computeIntervalDates();
        return $this->last_date;
    }

    public function getDuration(): ?\DateInterval {
        if ($this->last_date == null || $this->first_date == null)
            $this->computeIntervalDates();
        if ($this->last_date == null || $this->first_date == null)
            return null;
        return $this->first_date->diff($this->last_date);
    }

    private function computeIntervalDates() {
        /** @var ProductMet $product */
        foreach ($this->getProducts() as $product) {
            if ($this->first_date == null || $product->getDate() < $this->first_date)
                $this->first_date = $product->getDate();
            if ($this->last_date == null || $product->getDate() > $this->last_date)
                $this->last_date = $product->getDate();
        }
    }
}

