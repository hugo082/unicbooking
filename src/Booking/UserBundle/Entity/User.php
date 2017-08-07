<?php

namespace Booking\UserBundle\Entity;

use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Location;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * User
 *
 * @ORM\Table(name="booking_user")
 * @ORM\Entity(repositoryClass="Booking\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Location
     * @var Location
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Location", cascade={"persist"})
     * @ORM\JoinColumn(name="loca_id", referencedColumnName="id", nullable=true)
     */
    private $location;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Book", mappedBy="holder", cascade={"persist"})
     */
    protected $books;

    public function __construct()
    {
        parent::__construct();
        $this->books = new ArrayCollection();
    }

    /**
     * @return Location
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @Viewable(title="Location", index=3)
     * @return String
     */
    public function getLocationString(): String
    {
        return $this->location ?? "All";
    }

    /**
     * @param Location $location
     */
    public function setLocation(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Add book
     *
     * @param Book $book
     *
     * @return User
     */
    public function addBook(Book $book)
    {
        $this->books[] = $book;
        return $this;
    }

    /**
     * Remove book
     *
     * @param Book $book
     */
    public function removeBook(Book $book)
    {
        $this->books->removeElement($book);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @Viewable(title="Email", index=1)
     * @return string
     */
    public function getEmail()
    {
        return parent::getEmail();
    }

    /**
     * @Viewable(title="Username", index=0)
     * @return string
     */
    public function getUsername()
    {
        return parent::getUsername();
    }

    /**
     * @Viewable(title="Enabled", index=2)
     * @return string
     */
    public function isActive()
    {
        return parent::isEnabled() ? "YES" : "NO";
    }
}
