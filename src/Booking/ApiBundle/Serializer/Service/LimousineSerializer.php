<?php

namespace Booking\ApiBundle\Serializer\Service;

use Booking\ApiBundle\Exception\ApiException;
use Booking\ApiBundle\Serializer\Serializer;
use Booking\AppBundle\Entity\Metadata\Service\Limousine;

class LimousineSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if ($data == null)
            return null;
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Limousine)
            throw new ApiException("Serialization", "ServiceAirport attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "drop_off" => $data->getDropOff(),
            "pick_up" => $data->getPickUp(),
            "time" => $data->getTime(),
            "additional_stops" => $data->getAdditionalStops(),
            "start_mileage" => $data->getStartMileage()
        ];
    }
}