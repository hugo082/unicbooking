<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Product;

class ProductTypeSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Product)
            throw new ApiException("Serialization", "ProductType attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "name" => $data->getName(),
            "service" => $this->subSerialize($data->getService(), "booking.api.serializer.product.service"),
        ];
    }
}
