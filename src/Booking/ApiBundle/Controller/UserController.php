<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Serializer\BookSerializer;
use Booking\ApiBundle\Serializer\UserSerializer;
use Booking\AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/user/detail")
     */
    public function getCurrentUserAction(Request $request)
    {
        /** @var UserSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.user");
        return $serializer->serialize($this->getUser());
    }
}
