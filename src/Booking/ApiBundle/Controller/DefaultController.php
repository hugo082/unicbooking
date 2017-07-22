<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Checker\ApiChecker;
use Booking\ApiBundle\Exception\ApiException;
use Booking\ApiBundle\Serializer\FlightSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * @Rest\View()
     * @Rest\Get("/flight")
     */
    public function flightAction(Request $request)
    {
        $response = new JsonResponse();
        /** @var ApiChecker $apiChecker */
        $apiChecker = $this->get('booking.api.checker');

        $flight = null;
        try {
            $flight = $apiChecker->loadFlightWithRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();
            $statusResponse = array(
                'code' => 200
            );
        } catch (ApiException $e) {
            $statusResponse = $e->encode();
        }

        /** @var FlightSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.flight");
        $response->setData(array(
            "status" => $statusResponse,
            "flight" => $serializer->serialize($flight)
        ));

        return $response;
    }
    
}