<?php

namespace Booking\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FQT\DBCoreManagerBundle\Annotations\Viewable;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity(repositoryClass="Booking\AppBundle\Repository\LocationRepository")
 */
class Location
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
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="apiEnabled", type="boolean")
     */
    private $apiEnabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="highlighted", type="boolean")
     */
    private $highlighted;

    public function __toString()
    {
        return $this->name;
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
     * @return Location
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @Viewable(title="Name", index=1)
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set apiEnabled
     *
     * @param boolean $apiEnabled
     *
     * @return Location
     */
    public function setApiEnabled($apiEnabled)
    {
        $this->apiEnabled = $apiEnabled;

        return $this;
    }

    /**
     * Get apiEnabled
     *
     * @return bool
     */
    public function getApiEnabled()
    {
        return $this->apiEnabled;
    }

    /**
     * Get apiEnabled displayable value
     * @Viewable(title="Api Enabled", index=2)
     * @return string
     */
    public function getApiEnabledString() {
        return $this->apiEnabled ? "YES" : "NO";
    }

    /**
     * @return bool
     */
    public function isHighlighted(): bool
    {
        return $this->highlighted ?? false;
    }

    /**
     * @param bool $highlighted
     */
    public function setHighlighted(bool $highlighted)
    {
        $this->highlighted = $highlighted;
    }

    /**
     * Get Highlighted displayable value
     * @Viewable(title="Highlighted", index=3)
     * @return string
     */
    public function getHighlightedString() {
        return $this->highlighted ? "YES" : "NO";
    }

    /**
     * Get Highlighted displayable style
     * @return string
     */
    public function getHighlightedStyle() {
        return $this->highlighted ? "location-highlighted" : "";
    }
}

