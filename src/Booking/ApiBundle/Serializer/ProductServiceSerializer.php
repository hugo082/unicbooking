<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Service;

class ProductServiceSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Service)
            throw new ApiException("Serialization", "Service attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "name" => $data->getName(),
            "type" => $data->getType(),
            "icon_code" => $data->getIconCode(),
        ];
    }
}
