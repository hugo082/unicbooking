<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
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
     * @var boolean
     *
     * @ORM\Column(name="removed", type="boolean")
     */
    private $removed = false;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="passengers", type="integer")
     */
    private $passengers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="transport", type="boolean")
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
    * @var integer
    *
    * @ORM\Column(name="additionalprice", type="integer")
    */
    private $additionalprice;

    /**
    * @var Compagny
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
    * @ORM\JoinColumn(nullable=true)
    */
    private $compagny;

    /**
     * Get Full Name
     */
    public function getFullName()
    {
        $res = $this->name . " " . $this->price . "€ (" . $this->passengers;
        if ($this->passengers > 1) $res .=  " Persons)";
        else $res .=  " Person)";
        return $res;
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
     * @return Service
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
     * Set price
     *
     * @param integer $price
     *
     * @return Service
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set passengers
     *
     * @param integer $passengers
     *
     * @return Service
     */
    public function setPassengers($passengers)
    {
        $this->passengers = $passengers;

        return $this;
    }

    /**
     * Get passengers
     *
     * @return int
     */
    public function getPassengers()
    {
        return $this->passengers;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Service
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set compagny
     *
     * @param \AppBundle\Entity\Compagny $compagny
     *
     * @return Product
     */
    public function setCompagny(\AppBundle\Entity\Compagny $compagny = null)
    {
        $this->compagny = $compagny;

        return $this;
    }

    /**
     * Get compagny
     *
     * @return \AppBundle\Entity\Compagny
     */
    public function getCompagny()
    {
        return $this->compagny;
    }

    /**
     * Set transport
     *
     * @param boolean $transport
     *
     * @return Product
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return boolean
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set removed
     *
     * @param boolean $removed
     *
     * @return Product
     */
    public function setRemoved($removed)
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * Get removed
     *
     * @return boolean
     */
    public function getRemoved()
    {
        return $this->removed;
    }

    /**
     * Set additionalprice
     *
     * @param integer $additionalprice
     *
     * @return Product
     */
    public function setAdditionalprice($additionalprice)
    {
        $this->additionalprice = $additionalprice;

        return $this;
    }

    /**
     * Get additionalprice
     *
     * @return integer
     */
    public function getAdditionalprice()
    {
        return $this->additionalprice;
    }
}
