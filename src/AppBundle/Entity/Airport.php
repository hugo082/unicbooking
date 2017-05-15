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
     * @var boolean
     *
     * @ORM\Column(name="removed", type="boolean")
     */
    private $removed = false;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code_aita", type="string", length=255, unique=true)
     */
    private $code_aita;

    /**
     * @var string
     *
     * @ORM\Column(name="code_oaci", type="string", length=255, unique=true)
     */
    private $code_oaci;

    /**
     * @var boolean
     *
     * @ORM\Column(name="selectable", type="boolean")
    */
    private $selectable;

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
        return $this->code_aita . " - " . $this->name;
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

    /**
     * Set removed
     *
     * @param boolean $removed
     *
     * @return Airport
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

    /**
     * Set selectable
     *
     * @param boolean $selectable
     *
     * @return Airport
     */
    public function setSelectable($selectable)
    {
        $this->selectable = $selectable;

        return $this;
    }

    /**
     * Get selectable
     *
     * @return boolean
     */
    public function getSelectable()
    {
        return $this->selectable;
    }

    /**
     * Set codeAita
     *
     * @param string $codeAita
     *
     * @return Airport
     */
    public function setCodeAita($codeAita)
    {
        $this->code_aita = $codeAita;

        return $this;
    }

    /**
     * Get codeAita
     *
     * @return string
     */
    public function getCodeAita()
    {
        return $this->code_aita;
    }

    /**
     * Set codeOaci
     *
     * @param string $codeOaci
     *
     * @return Airport
     */
    public function setCodeOaci($codeOaci)
    {
        $this->code_oaci = $codeOaci;

        return $this;
    }

    /**
     * Get codeOaci
     *
     * @return string
     */
    public function getCodeOaci()
    {
        return $this->code_oaci;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        if ($this->code_oaci != $this->code_aita)
            return $this->code_aita . "(" . $this->code_oaci . ")";
        else
            return $this->code_oaci;
    }
}
