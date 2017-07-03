<?php

namespace Booking\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Client
 *
 * @ORM\Table(name="booking_client")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     * @ORM\Column(name="billing", type="string", length=3)
     */
    private $billing;

    /**
     * @var string
     * @ORM\Column(name="tariff", type="string", length=20)
     */
    private $tariff;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Contact", mappedBy="client", cascade={"persist"})
     */
    protected $contacts;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

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
     * Set code
     *
     * @param string $code
     *
     * @return Client
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     * @Viewable(title="Code", index=1)
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Client
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
     * Set type
     *
     * @param string $type
     *
     * @return Client
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     * @Viewable(title="Type", index=3)
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @Viewable(title="Billing", index=4)
     * @return string
     */
    public function getBilling()
    {
        return $this->billing;
    }

    /**
     * @param string $billing
     */
    public function setBilling(string $billing)
    {
        $this->billing = $billing;
    }

    /**
     * @Viewable(title="Tariff", index=5)
     * @return string
     */
    public function getTariff()
    {
        return $this->tariff;
    }

    /**
     * @param string $tariff
     */
    public function setTariff(string $tariff)
    {
        $this->tariff = $tariff;
    }

    /**
     * @return ArrayCollection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param ArrayCollection $contacts
     */
    public function setContacts(ArrayCollection $contacts)
    {
        $this->contacts = $contacts;
    }

    public function linkContacts() {
        /** @var Contact $contact */
        foreach ($this->contacts as $contact)
            $contact->setClient($this);
    }
}

