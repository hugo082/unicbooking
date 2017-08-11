<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Flight;

class FlightSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if ($data == null)
            return null;
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Flight)
            throw new ApiException("Serialization", "Flight attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "origin" => $this->subSerialize($data->getOrigin(), "booking.api.serializer.airport"),
            "destination" => $this->subSerialize($data->getDestination(), "booking.api.serializer.airport"),
            "arrival_time" => $this->date($data->getArrivalTime()),
            "departure_time" => $this->date($data->getDepartureTime()),
            "type" => $data->getType(),
            "codes" => $this->subSerialize($data->getCodes(), "booking.api.serializer.core.international_codes")
        ];
    }
}