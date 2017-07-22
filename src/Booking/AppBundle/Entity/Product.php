<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\Core\Price;
use FQT\DBCoreManagerBundle\Annotations\Viewable;
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
     * @var Service
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Service", cascade={"persist"})
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
     * @Viewable(title="id", index=0)
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
     * @Viewable(title="Name", index=2)
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @Viewable(title="Service", index=1)
     * @return Service
     */
    public function getService(): ?Service
    {
        return $this->service;
    }

    /**
     * @param Service $service
     */
    public function setService(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @return Client[]
     */
    public function getClients()
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
     * @Viewable(title="Price (TVA)", index=3)
     * @return Price
     */
    public function getPrice(): ?Price
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

