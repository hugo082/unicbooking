<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Compagny
 *
 * @ORM\Table(name="compagny")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompagnyRepository")
 */
class Compagny
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
     * @var string
     *
     * @ORM\Column(name="maincolor", type="string", length=7)
     */
    private $maincolor;

    /**
     * @var string
     *
     * @ORM\Column(name="secondcolor", type="string", length=7)
     */
    private $secondcolor;


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
     * @return Compagny
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
        return $this->name . "(" . $this->code . ")";
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Compagny
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
     * Set maincolor
     *
     * @param string $maincolor
     *
     * @return Compagny
     */
    public function setMaincolor($maincolor)
    {
        $this->maincolor = $maincolor;

        return $this;
    }

    /**
     * Get maincolor
     *
     * @return string
     */
    public function getMaincolor()
    {
        return $this->maincolor;
    }

    /**
     * Set secondcolor
     *
     * @param string $secondcolor
     *
     * @return Compagny
     */
    public function setSecondcolor($secondcolor)
    {
        $this->secondcolor = $secondcolor;

        return $this;
    }

    /**
     * Get secondcolor
     *
     * @return string
     */
    public function getSecondcolor()
    {
        return $this->secondcolor;
    }
}