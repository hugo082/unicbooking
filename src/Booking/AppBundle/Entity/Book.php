<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\Core\Agent;
use Booking\AppBundle\Entity\Core\Tax;
use Booking\AppBundle\Entity\Metadata\Execution;
use Booking\AppBundle\Manager\BookPriceManager;
use Booking\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Booking\AppBundle\Entity\Metadata\Product as ProductMet;
use Doctrine\ORM\EntityManager;
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
     * @var string
     * @ORM\Column(name="devices", type="string")
     */
    protected $devices;

    /**
     * @var string
     * @ORM\Column(name="bill_number", type="string", nullable=true)
     */
    protected $billNumber;

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
     * @var User
     * @ORM\ManyToOne(targetEntity="Booking\UserBundle\Entity\User", inversedBy="books", cascade={"persist"})
     */
    private $holder;

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
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Metadata\Product", mappedBy="book", cascade={"persist", "refresh"})
     */
    protected $products;

    /**
     * @var Tax[]
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Core\Tax", mappedBy="book", cascade={"persist", "refresh"})
     */
    protected $taxes;

    /**
     * @var \DateTime
     */
    private $first_date;
    /**
     * @var \DateTime
     */
    private $last_date;

    /**
     * @var null|BookPriceManager
     */
    private $priceManager;

    public function __construct()
    {
        $this->priceManager = null;
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
     * @return string
     */
    public function getDevices(): ?string
    {
        return $this->devices;
    }

    /**
     * @param string $devices
     */
    public function setDevices(string $devices)
    {
        $this->devices = $devices;
    }

    /**
     * @return string
     */
    public function getBillNumber(): ?string
    {
        return $this->billNumber;
    }

    /**
     * @param string $billNumber
     */
    public function setBillNumber(string $billNumber)
    {
        $this->billNumber = $billNumber;
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

    public function removeNotFoundOriginalProducts(EntityManager $em, array $original) {
        foreach ($this->getProducts() as $entity) {
            /**
             * @var int $key
             * @var array $data
             */
            foreach ($original as $key => $data) {
                /** @var \Booking\AppBundle\Entity\Metadata\Product $eToDel */
                $eToDel = $data["product"];
                if ($eToDel->getId() == $entity->getId()) {
                    $eToDel->removeNotFoundOriginalCustomers($em, $data["originalCustomers"]);
                    unset($original[$key]);
                    break;
                }
            }
        }
        foreach ($original as $data) {
            $eToDel = $data["product"];
            $this->products->removeElement($eToDel);
            $em->remove($eToDel);
        }
    }

    /**
     * @return Tax[]
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    public function getTaxesCopy(): array{
        $original = [];
        foreach ($this->getTaxes() as $tax)
            $original[] = $tax;
        return $original;
    }

    /**
     * @param Tax[] $taxes
     */
    public function setTaxes($taxes)
    {
        $this->taxes = $taxes;
    }

    public function removeNotFoundOriginalTaxes(EntityManager $em, array $original) {
        foreach ($this->getTaxes() as $entity) {
            /**
             * @var int $key
             * @var Tax $eToDel
             */
            foreach ($original as $key => $eToDel) {
                if ($eToDel->getId() == $entity->getId()) {
                    unset($original[$key]);
                    break;
                }
            }
        }
        foreach ($original as $eToDel) {
            $this->taxes->removeElement($eToDel);
            $em->remove($eToDel);
        }
    }

    public function linkSubEntities() {
        /** @var ProductMet $prod */
        foreach ($this->getProducts() as $prod) {
            $prod->setBook($this);
            $prod->linkSubEntities();
        }
        if (!empty($this->getTaxes())) {
            foreach ($this->getTaxes() as $tax)
                $tax->setBook($this);
        }
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creation_date;
    }

    /**
     * @return User
     */
    public function getHolder(): ?User
    {
        return $this->holder;
    }

    /**
     * @param User $holder
     */
    public function setHolder(User $holder)
    {
        $this->holder = $holder;
    }

    public function getState(bool $value = false): string
    {
        $state = Execution::EMPTY_STATE[(int)$value];
        foreach ($this->products as $product)
            $state = $product->getExecution()->computeState($state, $value);
        return $state;
    }

    /**
     * @return BookPriceManager
     */
    public function getPriceManager(): BookPriceManager
    {
        if ($this->priceManager === null)
            $this->priceManager = new BookPriceManager($this);
        return $this->priceManager;
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

