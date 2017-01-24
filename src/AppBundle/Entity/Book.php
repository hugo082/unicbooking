<?php

namespace AppBundle\Entity;

use AppBundle\Model\Book as BaseBook;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Book
*
* @ORM\Table(name="book")
* @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
*/
class Book extends BaseBook
{
    /**
    * @var int
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

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
    * @var User
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
    * @ORM\JoinColumn(nullable=true)
    */
    protected $driver;

    /**
    * @var User
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee")
    * @ORM\JoinColumn(nullable=true)
    */
    protected $greeter;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Customer", mappedBy="book", cascade={"persist"})
    */
    protected $customers;

    /**
    * Constructor
    */
    public function __construct()
    {
        parent::__construct();
        $this->customers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->enabled = false;
    }

    /**
    * Add $value at book.price
    */
    public function addToPrice($value) {
        $this->price += $value;
        return $this;
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
}
