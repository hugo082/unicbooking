<?php

namespace Booking\ApiBundle\Serializer\Core;

use Booking\ApiBundle\Exception\ApiException;
use Booking\ApiBundle\Serializer\Serializer;
use Booking\AppBundle\Entity\InternationalCodes\InternationalCodes;

class InternationalCodesSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof InternationalCodes)
            throw new ApiException("Serialization", "Book attempt " . get_class($data) . " given", 300);

        return [
            "master" => $data->getCode(),
            "icao" => $data->getIcao(),
            "iata" => $data->getIata()
        ];
    }
}