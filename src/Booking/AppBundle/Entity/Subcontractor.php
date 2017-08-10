<?php
/**
 * Created by PhpStorm.
 * User: hugofouquet
 * Date: 10/08/2017
 * Time: 16:19
 */

namespace Booking\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Client
 *
 * @ORM\Table(name="booking_sub_contractor")
 * @ORM\Entity()
 */
class Subcontractor
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     *
     * @return Subcontractor
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
}

