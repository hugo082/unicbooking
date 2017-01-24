<?php

namespace AppBundle\Entity;

use AppBundle\Model\Book as BaseBook;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
* Book
*
* @ORM\Table(name="subbook")
* @ORM\Entity(repositoryClass="AppBundle\Repository\SubBookRepository")
*/
class SubBook extends BaseBook
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
    * @var Book
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book")
    * @ORM\JoinColumn(nullable=false)
    */
    protected $parent;

    /**
    * Constructor
    */
    public function __construct($book)
    {
        parent::__construct();
        $this->airport = $book->getAirport();
        $this->date = $book->getDate();
        $this->service = $book->getService();
        $this->product = $book->getProduct();
        $this->flight = $book->getFlight();
        $this->adultcus = $book->getAdultcus();
        $this->childcus = $book->getChildcus();
        $this->nameboard = $book->getNameboard();
        $this->bags = $book->getBags();
        $this->agentfirstname = $book->getAgentfirstname();
        $this->agentlastname = $book->getAgentlastname();
        $this->agentemail = $book->getAgentemail();
        $this->price = $book->getPrice();
        $this->timepu = $book->getTimepu();
        $this->addresspu = $book->getAddresspu();
        $this->addressdo = $book->getAddressdo();
        $this->note = $book->getNote();
        $this->parent = $book;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Book $parent
     *
     * @return SubBook
     */
    public function setParent(\AppBundle\Entity\Book $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Book
     */
    public function getParent()
    {
        return $this->parent;
    }
}
