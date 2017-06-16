<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

use Doctrine\ORM\Mapping as ORM;

/**
 * Train Service
 *
 * @ORM\Embeddable
 */
class Train
{
    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="station", type="string", length=255)
     */
    protected $station;

    /**
     * @var string
     * @ORM\Column(name="time", type="time")
     */
    protected $time;
}
