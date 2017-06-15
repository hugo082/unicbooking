<?php

namespace Booking\UserBundle\Entity;

use Booking\AppBundle\Entity\Book;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Book", mappedBy="user", cascade={"persist"})
     */
    protected $books;

    public function __construct()
    {
        parent::__construct();
        $this->books = new ArrayCollection();
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
}
