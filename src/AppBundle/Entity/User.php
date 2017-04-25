<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* User
*
* @ORM\Entity
* @ORM\Table(name="fos_user")
* @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
* @ORM\AttributeOverrides({
*              @ORM\AttributeOverride(name="email", column=@ORM\Column(nullable=true)),
*              @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(nullable=true, unique=false))
*
* })
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
    * @var boolean
    *
    * @ORM\Column(name="removed", type="boolean")
    */
    private $removed = false;

    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Book", mappedBy="user", cascade={"persist"})
    */
    protected $books;

    /**
    * @var Compagny
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
    * @ORM\JoinColumn(nullable=true)
    */
    private $compagny;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function setUsername($username){
        parent::setUsername($username);
        //$this->setEmail($username . "@email.com");
        return $this;
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
    * Set compagny
    *
    * @param \AppBundle\Entity\Compagny $compagny
    *
    * @return User
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
}
