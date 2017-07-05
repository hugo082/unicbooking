<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Serializer\BookSerializer;
use Booking\AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/book/{id}")
     */
    public function getBookAction(Request $request)
    {
        $book = $this->getDoctrine()->getRepository('BookingAppBundle:Book')->find($request->get('id'));
        if (!$book instanceof Book)
            return new JsonResponse(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);

        /** @var BookSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.book");
        return $serializer->serialize($book);
    }
}
