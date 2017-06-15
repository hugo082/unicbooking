<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\Core\Price;
use Booking\AppBundle\Entity\Services\Basic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="booking_product")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\ProductRepository")
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
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(name="tva", type="integer")
     */
    private $tva;

    /**
     * @var Basic
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Services\Basic", cascade={"persist"})
     */
    private $service;

    /**
     * @var Price
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Core\Price", columnPrefix="price_")
     */
    private $price;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Booking\AppBundle\Entity\Client")
     * @ORM\JoinTable(name="booking_products_clients",
     *      joinColumns={@ORM\JoinColumn(name="client_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tva
     *
     * @param integer $tva
     *
     * @return Product
     */
    public function setTva($tva)
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * Get tva
     *
     * @return int
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @return Basic
     */
    public function getService(): Basic
    {
        return $this->service;
    }

    /**
     * @param Basic $service
     */
    public function setService(Basic $service)
    {
        $this->service = $service;
    }

    /**
     * @return ArrayCollection
     */
    public function getClients(): ArrayCollection
    {
        return $this->clients;
    }

    /**
     * @param ArrayCollection $clients
     */
    public function setClients(ArrayCollection $clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * @param Price $price
     */
    public function setPrice(Price $price)
    {
        $this->price = $price;
    }
}

