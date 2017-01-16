<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
* @ORM\Entity
* @ORM\Table(name="fos_user")
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
    * @var string
    *
    * @ORM\Column(name="firstname", type="string", length=255)
    * @Assert\NotBlank(message="Please enter your name.", groups={"Registration", "Profile"})
    */
    private $firstname;

    /**
    * @var string
    *
    * @ORM\Column(name="lastname", type="string", length=255)
    */
    private $lastname;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Book", mappedBy="user", cascade={"persist"})
    */
    protected $books;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
    * Add book
    *
    * @param \AppBundle\Entity\Book $book
    *
    * @return User
    */
    public function addBook(\AppBundle\Entity\Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
    * Remove book
    *
    * @param \AppBundle\Entity\Book $book
    */
    public function removeBook(\AppBundle\Entity\Book $book)
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
    * Set firstname
    *
    * @param string $firstname
    *
    * @return User
    */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
    * Get firstname
    *
    * @return string
    */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
    * Set lastname
    *
    * @param string $lastname
    *
    * @return User
    */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
    * Get lastname
    *
    * @return string
    */
    public function getLastname()
    {
        return $this->lastname;
    }
}
