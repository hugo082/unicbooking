<?php
/**
 * Created by PhpStorm.
 * User: hugofouquet
 * Date: 10/08/2017
 * Time: 18:15
 */


namespace Booking\AppBundle\Entity\Core;

use Booking\AppBundle\Entity\Book;
use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Location
 *
 * @ORM\Table(name="booking_tax")
 * @ORM\Entity()
 */
class Tax
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
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var Price
     * @ORM\Embedded(class="Booking\AppBundle\Entity\Core\Price", columnPrefix="price_")
     */
    private $price;

    /**
     * @var Book
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Book", inversedBy="taxs")
     * @ORM\JoinColumn(name="book", referencedColumnName="id")
     */
    private $book;

    public function __toString()
    {
        return $this->title;
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
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
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

    /**
     * @return Book
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book $book
     */
    public function setBook(Book $book)
    {
        $this->book = $book;
    }
}

