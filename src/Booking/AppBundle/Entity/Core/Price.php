<?php

namespace Booking\AppBundle\Entity\Core;

use Doctrine\ORM\Mapping as ORM;

/**
 * Price
 *
 * @ORM\Embeddable
 */
class Price
{
    /**
     * @var integer
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var integer
     * @ORM\Column(name="tva", type="integer", nullable=true)
     */
    private $tva;

    public function __toString()
    {
        if ($this->tva == null)
            return "" . $this->count;
        return $this->count . " (" . $this->tva . ")";
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param int $tva
     */
    public function setTva(int $tva)
    {
        $this->tva = $tva;
    }
}