<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Customer;

class CustomerSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Customer)
            throw new ApiException("Serialization", "Customer attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "title" => $data->getTitle(),
            "first_name" => $data->getFirstname(),
            "last_name" => $data->getLastname(),
            "cabin" => $data->getCabin(),
            "phone" => $data->getPhone(),
            "sexe" => $data->getSexe(),
        ];
    }
}