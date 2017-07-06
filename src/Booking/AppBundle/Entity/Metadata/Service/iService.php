<?php

namespace Booking\AppBundle\Entity\Metadata\Service;

interface iService
{
    /**
     * Compute if service have necessary fields.
     * @return bool
     */
    public function isValid(): Bool;
}

