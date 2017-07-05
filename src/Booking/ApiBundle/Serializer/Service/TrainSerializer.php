<?php

namespace Booking\ApiBundle\Serializer\Service;

use Booking\ApiBundle\Exception\ApiException;
use Booking\ApiBundle\Serializer\Serializer;
use Booking\AppBundle\Entity\Metadata\Service\Train;

class TrainSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if ($data == null)
            return null;
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Train)
            throw new ApiException("Serialization", "ServiceAirport attempt " . get_class($data) . " given", 300);

        return [
            "code" => $data->getCode(),
            "station" => $data->getStation(),
            "time" => $data->getTime()
        ];
    }
}