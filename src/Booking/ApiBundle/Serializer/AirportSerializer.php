<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Airport;

class AirportSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Airport)
            throw new ApiException("Serialization", "Book attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "name" => $data->getName(),
            "codes" => $this->subSerialize($data->getCodes(), "booking.api.serializer.core.international_codes")
        ];
    }
}