<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Metadata\Product as ProductMet;

class ProductMetadataSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if ($data === null)
            return null;
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof ProductMet)
            throw new ApiException("Serialization", "ProductMet attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "airport" => $this->subSerialize($data->getAirport(), "booking.api.serializer.service.airport"),
            "limousine" => $this->subSerialize($data->getLimousine(), "booking.api.serializer.service.limousine"),
            "train" => $this->subSerialize($data->getTrain(), "booking.api.serializer.service.train"),
            "type" => $this->subSerialize($data->getProductType(), "booking.api.serializer.product.type"),
            "execution" => $this->subSerialize($data->getExecution(), "booking.api.serializer.execution"),
            "customers" => $this->subSerialize($data->getCustomers(), "booking.api.serializer.customer"),
            "baggage" => $data->getBaggages(),
            "note" => $data->getNote(),
            "location" => $data->getLocation()->getName(),
            "date" => $data->getDate()->getTimestamp(),
            "is_child" => $data->isChild(),
            "index" => $data->getIndex(),
            "linked" => $this->subSerialize($data->getLinkedProduct(), "booking.api.serializer.product.metadata")
        ];
    }
}