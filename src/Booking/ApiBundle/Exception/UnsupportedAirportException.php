<?php

namespace Booking\ApiBundle\Exception;

use Booking\AppBundle\Entity\Airport;
use Throwable;

class UnsupportedAirportException extends ApiException
{
    public const TYPE_AIRPORT = "airport";
    public const TYPE_CONNECTION = "connection";

    /**
     * @var string
     */
    private $type;

    /**
     * @var Airport
     */
    private $airport;

    public function __construct($type, Airport $airport = null, Throwable $previous = null)
    {
        $this->type = $type;
        $this->airport = $airport;
        parent::__construct("Unsupported airport", $this->computeMessage(), 400, $previous);
    }

    private function computeMessage(): string {
        if ($this->type == self::TYPE_CONNECTION)
            return "Unable to establish connection between flights";
        else if ($this->type == self::TYPE_AIRPORT) {
            if ($this->airport !== null)
                return "Airport with code " . $this->airport->getCodes()->getCode() . " is not supported yet";
            else
                return "Unable to determine the location of the operation";
        }
        return "Unknow";
    }
}