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
     * @ORM\Column(name="tva", type="integer")
     */
    private $tva;
}