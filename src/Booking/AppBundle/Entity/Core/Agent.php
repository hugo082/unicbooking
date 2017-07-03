<?php

namespace Booking\AppBundle\Entity\Core;

use Doctrine\ORM\Mapping as ORM;

/**
 * Booking Agent
 *
 * @ORM\Embeddable
 */
class Agent
{
    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string")
     */
    private $first_name;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string")
     */
    private $last_name;

    public function __toString()
    {
        return $this->last_name . " " . $this->first_name[0];
    }

    public function fullName(): string {
        return $this->last_name . " " . $this->first_name;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName(string $last_name)
    {
        $this->last_name = $last_name;
    }
}