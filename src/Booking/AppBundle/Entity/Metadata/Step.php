<?php

namespace Booking\AppBundle\Entity\Metadata;

use Doctrine\ORM\Mapping as ORM;

/**
 * Step of execution
 *
 * @ORM\Table(name="booking_product_execution_step")
 * @ORM\Entity()
 */
class Step
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
     * @ORM\Column(name="title", type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="icon", type="string")
     */
    private $icon;

    /**
     * @var \DateTime
     * @ORM\Column(name="finish_time", type="datetime", nullable=true)
     */
    private $finish_time;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var Execution
     * @ORM\ManyToOne(targetEntity="Booking\AppBundle\Entity\Metadata\Execution", inversedBy="steps")
     */
    private $execution;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return \DateTime
     */
    public function getFinishTime(): ?\DateTime
    {
        return $this->finish_time;
    }

    public function finish()
    {
        $this->finish_time = new \DateTime();
    }

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote(string $note)
    {
        $this->note = $note;
    }

    /**
     * @return Execution
     */
    public function getExecution(): ?Execution
    {
        return $this->execution;
    }

    /**
     * @param Execution $execution
     */
    public function setExecution(Execution $execution)
    {
        $this->execution = $execution;
    }

    public static function with(string $title, string $icon, Execution $execution) {
        $step = new Step();
        $step->setTitle($title);
        $step->setExecution($execution);
        $step->setIcon($icon);
        $execution->pushStep($step);
        return $step;
    }
}