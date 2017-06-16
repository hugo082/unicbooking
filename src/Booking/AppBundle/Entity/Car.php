<?php

namespace Booking\AppBundle\Entity;

use Booking\AppBundle\Entity\Core\Price;
use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Car
 *
 * @ORM\Table(name="booking_car")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\CarRepository")
 */
class Car
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
     * @var Price
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Core\Price", columnPrefix="price_")
     */
    private $price;


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
     * @param string $name
     * @return Car
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @Viewable(title="Name", index=1)
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @Viewable(title="Price", index=2)
     * @return Price
     */
    public function getPrice()
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

