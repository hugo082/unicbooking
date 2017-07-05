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
     * @var \DateTime
     * @ORM\Column(name="finish_time", type="datetime")
     */
    private $finish_time;

    /**
     * @var string
     * @ORM\Column(name="note", type="text")
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
     * @return \DateTime
     */
    public function getFinishTime(): ?\DateTime
    {
        return $this->finish_time;
    }

    /**
     * @param \DateTime $finish_time
     */
    public function setFinishTime(\DateTime $finish_time)
    {
        $this->finish_time = $finish_time;
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

    public static function with(string $title, Execution $execution) {
        $step = new Step();
        $step->setTitle($title);
        $step->setExecution($execution);
        return $step;
    }
}