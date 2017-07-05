<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Book;

class BookSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Book)
            throw new ApiException("Serialization", "Book attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "products" => $this->subSerialize($data->getProducts(), "booking.api.serializer.product.metadata")
        ];
    }
}