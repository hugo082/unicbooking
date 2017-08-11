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

    public function getTtc(Client $client = null, int $override = null)
    {
        if ($override !== null)
            return $this->computeTtc($override, $client);
        return $this->computeTtc($this->count, $client);
    }

    public function appliedTo(int $price) {
        return $price * ($this->tva / 100) + $this->count;
    }

    public function getAmount(bool $ttc = true, Client $client = null, int $override = null) {
        if ($ttc)
            return $this->getTtc($client, $override);
        if ($override !== null)
            return $override;
        return $this->count;
    }

    private function computeTtc(int $count, Client $client = null) {
        $tva = $this->getTva($client) / 100;
        $ttc = $count / (1 + $tva) * $tva + $count;
        return round($ttc, 1);
    }

}