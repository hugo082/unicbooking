<?php

namespace Booking\AppBundle\Entity\Services;

use Doctrine\ORM\Mapping as ORM;

/**
 * Train Service
 *
 * @ORM\Table(name="booking_service_train")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\ServiceRepository")
 */
class Train extends Basic
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
