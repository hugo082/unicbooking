<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Metadata\Execution;

class ExecutionSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof Execution)
            throw new ApiException("Serialization", "Execution attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "current_step" => $data->getCurrentStep(),
            "state" => $data->getState(true),
            "steps" => $this->subSerialize($data->getSteps(), "booking.api.serializer.execution.step")
        ];
    }
}