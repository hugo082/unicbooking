<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    * @ORM\Column(name="code", type="string", length=255, unique=true)
    */
    private $code;

    /**
    * @var integer
    *
    * @ORM\Column(name="portageprice", type="integer")
    */
    private $portageprice;

    /**
    * @var string
    *
    * @ORM\Column(name="color", type="string", length=8)
    */
    private $color;

    /**
    * @ORM\Column(name="logo", type="string")
    *
    * @Assert\File(mimeTypes = {
    *          "image/png",
    *          "image/jpeg",
    *          "image/jpg",
    *          "image/svg+xml"
    *      })
    */
    private $logo;


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
    * Set removed
    *
    * @param boolean $removed
    *
    * @return Compagny
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
    * Set logo
    *
    * @param string $logo
    *
    * @return Compagny
    */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
    * Get logo
    *
    * @return string
    */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Compagny
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set portageprice
     *
     * @param integer $portageprice
     *
     * @return Compagny
     */
    public function setPortageprice($portageprice)
    {
        $this->portageprice = $portageprice;

        return $this;
    }

    /**
     * Get portageprice
     *
     * @return integer
     */
    public function getPortageprice()
    {
        return $this->portageprice;
    }
}
