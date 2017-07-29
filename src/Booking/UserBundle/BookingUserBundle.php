<?php

namespace Booking\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BookingUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
