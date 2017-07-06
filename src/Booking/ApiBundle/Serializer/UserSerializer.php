<?php

namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Metadata\Step;
use Booking\UserBundle\Entity\User;

class UserSerializer extends Serializer
{
    public function serialize($data): ?array
    {
        if (($res = parent::serialize($data)) !== null)
            return $res;

        if (!$data instanceof User)
            throw new ApiException("Serialization", "User attempt " . get_class($data) . " given", 300);

        return [
            "id" => $data->getId(),
            "username" => $data->getUsername(),
            "email" => $data->getEmail(),
            "roles" => $data->getRoles()
        ];
    }
}