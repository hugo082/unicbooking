<?php

namespace Booking\AppBundle\Entity\Core;

use Booking\AppBundle\Entity\Client;
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
     * @param Client|null $client
     * @return int
     */
    public function getTva(Client $client = null)
    {
        if ($client == null || $client->haveTva())
            return $this->tva;
        return 0;
    }

    /**
     * @param int $tva
     */
    public function setTva(int $tva)
    {
        $this->tva = $tva;
    }

    public function getTtc(Client $client = null)
    {
        $tva = $this->getTva($client) / 100;
        $ttc = $this->count / (1 + $tva) * $tva + $this->count;
        return round($ttc, 1);
    }

}