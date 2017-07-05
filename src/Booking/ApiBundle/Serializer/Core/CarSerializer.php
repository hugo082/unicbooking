<?php

namespace Booking\ApiBundle\Serializer\Core;

use Booking\ApiBundle\Exception\ApiException;
use Booking\ApiBundle\Serializer\Serializer;
use Booking\AppBundle\Entity\Car;

class CarSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if ($data == null)
            return null;
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Car)
            throw new ApiException("Serialization", "Car attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "name" => $data->getName()
        ];
    }
}