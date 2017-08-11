<?php

namespace Booking\AppBundle\Entity\Metadata;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product Metadata
 *
 * @ORM\Table(name="booking_product_execution")
 * @ORM\Entity()
 */
class Execution
{
    /** Execution not started */
    public const WAITING_STATE = ["default", "Waiting"];
    /** Execution in progress */
    public const PROGRESS_STATE = ["warning", "In progress" ];
    /** Book have product finished but not all */
    public const PENDING_STATE = ["primary", "Pending"];
    /** Execution finished */
    public const FINISHED_STATE = ["success", "Finished"];
    /** Used if book products is empty */
    public const EMPTY_STATE = ["danger", "Empty"];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Current execution Step
     * @var int
     * @ORM\Column(name="current_step", type="integer", nullable=true)
     */
    private $current_step;

    /**
     * @var Step[]
     * @ORM\OneToMany(targetEntity="Booking\AppBundle\Entity\Metadata\Step", mappedBy="execution", cascade={"persist", "remove"})
     */
    private $steps;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
    }

    public function updateCurrentStep(int $step, string $note) {
        $len = min(count($this->steps), $step);
        $start = max($this->getCurrentStep(true), 0);
        for($i = $start; $i < $len; $i++)
            $this->steps[$i]->finish($note);
        $this->current_step = $len;
    }

    public function getState(bool $value = false): string
    {
        if (!$this->isStarted())
            return self::WAITING_STATE[$value];
        if ($this->isInProgress())
            return self::PROGRESS_STATE[$value];
        return self::FINISHED_STATE[$value];
    }

    public function computeState(string $master_state, bool $value = false): string
    {
        $state = $this->getState($value);
        if ($master_state == self::EMPTY_STATE[(int)$value] || $master_state == $state)
            return $state;
        if ($master_state == Execution::PROGRESS_STATE)
            return Execution::PROGRESS_STATE[$value];
        return self::PENDING_STATE[$value];
    }

    public function isStarted(): bool
    {
        return $this->current_step !== null;
    }

    public function isInProgress(): bool
    {
        return $this->isStarted() && $this->current_step < count($this->steps);
    }

    public function isFinished(): bool
    {
        return $this->isStarted() && $this->current_step == count($this->steps);
    }

    public function setAirportConnectionSteps() {
        $this->steps[] = Step::with("Flight arrival", "icn_flight_arrival",$this);
        $this->steps[] = Step::with("Welcome passenger", "icn_passenger",$this);
        $this->steps[] = Step::with("Lounge VIP", "icn_baggage",$this);
        $this->steps[] = Step::with("Flight departure", "icn_flight_departure",$this);
    }

    public function setAirportDepartureSteps() {
        $this->steps[] = Step::with("Arrival passenger", "icn_passenger",$this);
        $this->steps[] = Step::with("Luggage porter", "icn_baggage",$this);
        $this->steps[] = Step::with("Check in", "icn_passport",$this);
        $this->steps[] = Step::with("Lounge VIP", "icn_baggage",$this);
        $this->steps[] = Step::with("Flight departure", "icn_flight_departure",$this);
    }

    public function setAirportArrivalSteps() {
        $this->steps[] = Step::with("Flight arrival", "icn_flight_arrival",$this);
        $this->steps[] = Step::with("Welcome passenger", "icn_passenger",$this);
        $this->steps[] = Step::with("Passport control", "icn_passport",$this);
        $this->steps[] = Step::with("Baggage", "icn_baggage",$this);
        $this->steps[] = Step::with("Car drop", "icn_car",$this);
    }

    public function setLimousineSteps() {
        $this->steps[] = Step::with("Drop off", "icn_passenger",$this);
        $this->steps[] = Step::with("Pick up", "icn_passenger",$this);
    }

    public function setTrainSteps() {
        $this->steps[] = Step::with("Train arrival", "icn_train",$this);
    }

    public function setEmptySteps() {
        $this->steps[] = Step::with("Empty", "icn_flight_departure",$this);
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCurrentStep(bool $force_int = false): ?int
    {
        if ($force_int)
            return $this->current_step ? $this->current_step : -1;
        return $this->current_step;
    }

    /**
     * @param int $current_step
     */
    public function setCurrentStep(int $current_step)
    {
        $this->current_step = $current_step;
    }

    /**
     * @return ArrayCollection
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param Step $step
     */
    public function pushStep(Step $step)
    {
        $this->steps[] = $step;
    }

    /**
     * @param ArrayCollection $steps
     */
    public function setSteps(ArrayCollection $steps)
    {
        $this->steps = $steps;
    }

}