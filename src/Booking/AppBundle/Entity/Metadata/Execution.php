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

    /** Tag to link execution step */
    private const LINK_STEP_TAG = "link_info";

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
     * @ORM\OrderBy({"index" = "ASC"})
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
        $this->steps[] = Step::with("Flight arrival", 1, "icn_flight_arrival",$this);
        $this->steps[] = Step::with("Welcome passenger", 2, "icn_passenger",$this);
        $this->steps[] = Step::with("Lounge VIP", 3, "icn_baggage",$this);
        $this->steps[] = Step::with("Flight departure", 4, "icn_flight_departure",$this);
    }

    public function setAirportDepartureSteps() {
        $this->steps[] = Step::with("Arrival passenger", 1,"icn_passenger",$this);
        $this->steps[] = Step::with("Luggage porter", 2,"icn_baggage",$this, "bag_count");
        $this->steps[] = Step::with("Check in", 3,"icn_passport",$this);
        $this->steps[] = Step::with("Lounge VIP", 4,"icn_baggage",$this);
        $this->steps[] = Step::with("Flight departure", 5,"icn_flight_departure",$this);
    }

    public function setAirportArrivalSteps() {
        $this->steps[] = Step::with("Flight arrival", 1,"icn_flight_arrival",$this);
        $this->steps[] = Step::with("Welcome passenger", 2,"icn_passenger",$this);
        $this->steps[] = Step::with("Passport control", 3,"icn_passport",$this);
        $this->steps[] = Step::with("Baggage", 4,"icn_baggage",$this, "bag_count");
        $this->steps[] = Step::with("Car drop", 5,"icn_car",$this);
    }

    public function setLimousineSteps() {
        $this->steps[] = Step::with("Drop off", 1,"icn_passenger",$this);
        $this->steps[] = Step::with("Pick up", 2,"icn_passenger",$this);
        $this->steps[] = Step::with("Add stop", 98,"icn_plus",$this, "add_stop");
    }

    public function setTrainSteps() {
        $this->steps[] = Step::with("Train arrival", 1,"icn_train",$this);
    }

    public function setEmptyStep() {
        $this->steps[] = Step::with("Empty", 1,"icn_flight_departure",$this);
    }

    public function setLinkStep(Product $product) {
        $tag = self::LINK_STEP_TAG . "$" . $product->getId();
        $this->steps[] = Step::with("Next step", 99,"icn_link",$this, $tag);
    }

    public function setEndStep() {
        $this->steps[] = Step::with("Finish", 100,"icn_finish",$this, "finish");
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
     * Push step at index
     * @param Step $step
     * @param null $at
     */
    public function pushStep(Step $step, $at = null)
    {
        $step->setExecution($this);
        if ($at === null)
            $this->steps[] = $step;
        else {
            $c = $this->steps->count();
            $buf = $step;
            for ($i = $at; $i < $c; $i++) {
                $temp = $this->steps->get($i);
                $this->steps->set($i, $buf);
                $buf = $temp;
            }
            $this->steps->set($c, $buf);
        }
    }

    public function pushLinkStep(Product $product) {
        $this->removeLinkStep();
        $this->setLinkStep($product);
    }

    public function removeLinkStep() {
        foreach ($this->steps as $key => $step) {
            if ($this->isLinkStep($step))
                $step->setExecution(null);
        }
    }

    /**
     * @param ArrayCollection $steps
     */
    public function setSteps(ArrayCollection $steps)
    {
        $this->steps = $steps;
    }

    private function isLinkStep(Step $step): Bool
    {
        return substr($step->getTag(), 0, strlen(self::LINK_STEP_TAG)) === self::LINK_STEP_TAG;
    }
}