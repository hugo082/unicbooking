<?php

namespace Booking\AppBundle\Entity\Core;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Embeddable
 */
class Address
{
    /**
     * @var string
     * @ORM\Column(name="station", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;
}