<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\Core\Agent;
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
     * Booking Agent
     * @var Agent
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Core\Agent", columnPrefix="age_")
     */
    private $agent;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Client", cascade={"persist"})
     */
    private $client;

    /**
     * @var \DateTime
     * @ORM\Column(name="creation_date", type="date")
     */
    protected $creation_date;

    /**
     * @var bool
     * @ORM\Column(name="archived", type="boolean")
     */
    protected $archived;

    /**
     * @var ProductMet[]
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

    private $price;

    public function __construct()
    {
        $this->price = null;
        $this->creation_date = new \DateTime();
        $this->products = new ArrayCollection();
        $this->archived = false;
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
     * @return Agent
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     */
    public function setAgent(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return Client
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return bool
     */
    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived(bool $archived)
    {
        $this->archived = $archived;
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

    public function getDates(): string {
        $this->computeIntervalDates();
        if ($this->last_date == null)
            return $this->secureDateToString($this->first_date,"d-M-Y");
        return $this->secureDateToString($this->first_date,"d-M-Y") . " - " . $this->secureDateToString($this->last_date,"d-M-Y");
    }

    public function getFirstDate(): ?\DateTime {
        $this->computeIntervalDates();
        return $this->first_date;
    }

    public function getLastDate(): ?\DateTime {
        $this->computeIntervalDates();
        return $this->last_date;
    }

    public function getDuration(): ?\DateInterval {
        $this->computeIntervalDates();
        if ($this->last_date == null || $this->first_date == null)
            return new \DateInterval("P0D");
        return $this->first_date->diff($this->last_date);
    }

    public function getPrice(bool $force = false) {
        if ($force || $this->price == null)
            $this->computePrice();
        return round($this->price, 1);
    }

    private function computePrice() {
        $this->price = 0;
        foreach ($this->products as $product)
            $this->price += $product->getPrice($this->client);
    }

    private function computeIntervalDates(bool $force = false) {
        if (!$force && $this->first_date != null)
            return false;
        /** @var ProductMet $product */
        foreach ($this->getProducts() as $product) {
            if ($this->first_date == null || $product->getDate() < $this->first_date)
                $this->first_date = $product->getDate();
            if ($this->first_date != $product->getDate() && ($this->last_date == null || $product->getDate() > $this->last_date))
                $this->last_date = $product->getDate();
        }
        return true;
    }

    private function secureDateToString(?\DateTime $date, string $format): string {
        if ($date == null)
            return "";
        return $date->format($format);
    }
}

