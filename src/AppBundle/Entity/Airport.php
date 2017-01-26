<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Airport
 *
 * @ORM\Table(name="airport")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AirportRepository")
 */
class Airport
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
    * @var Compagny
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Compagny")
    * @ORM\JoinColumn(nullable=true)
    */
    private $compagny;


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
     * @return Airport
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
     * Get name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->code . " - " . $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Airport
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
     * @return Airport
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
}
