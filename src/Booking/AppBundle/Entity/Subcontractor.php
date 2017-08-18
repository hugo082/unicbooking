<?php
/**
 * Created by PhpStorm.
 * User: hugofouquet
 * Date: 10/08/2017
 * Time: 16:19
 */

namespace Booking\AppBundle\Entity;

use Booking\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Client
 *
 * @ORM\Table(name="booking_sub_contractor")
 * @ORM\Entity()
 */
class Subcontractor
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
     * @var User
     * @ORM\ManyToOne(targetEntity="Booking\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="driver_id", referencedColumnName="id", nullable=true)
     */
    private $driver;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Booking\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="greeter_id", referencedColumnName="id", nullable=true)
     */
    private $greeter;

    public function __toString()
    {
        return $this->getName();
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
     * @return Subcontractor
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
     * @return User
     */
    public function getGreeter(): ?User
    {
        return $this->greeter;
    }

    /**
     * @param User $greeter
     */
    public function setGreeter(User $greeter)
    {
        $this->greeter = $greeter;
    }

    /**
     * @return User
     */
    public function getDriver(): ?User
    {
        return $this->driver;
    }

    /**
     * @param User $driver
     */
    public function setDriver(User $driver)
    {
        $this->driver = $driver;
    }
}

