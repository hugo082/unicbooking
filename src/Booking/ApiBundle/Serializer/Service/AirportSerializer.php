<?php

namespace Booking\ApiBundle\Serializer\Service;

use Booking\ApiBundle\Exception\ApiException;
use Booking\ApiBundle\Serializer\Serializer;
use Booking\AppBundle\Entity\Metadata\Service\Airport;

class AirportSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if ($data == null)
            return null;
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Airport)
            throw new ApiException("Serialization", "ServiceAirport attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "flight" => $this->subSerialize($data->getFlight(), "booking.api.serializer.flight"),
            "flight_transit" => $this->subSerialize($data->getFlightTransit(), "booking.api.serializer.flight"),
            "code" => $data->getCode()
        ];
    }
}