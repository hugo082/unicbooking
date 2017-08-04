<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Metadata\Step;

class StepSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Step)
            throw new ApiException("Serialization", "Step attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "title" => $data->getTitle(),
            "finish_time" => $data->getFinishTime() ? $data->getFinishTime()->getTimestamp() : null,
            "icon_name" => $data->getIcon(),
            "note" => $data->getNote()
        ];
    }
}