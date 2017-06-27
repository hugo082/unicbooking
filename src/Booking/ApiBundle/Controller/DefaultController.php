<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Checker\ApiChecker;
use Booking\ApiBundle\Exception\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/flight")
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
            $flight = $flight->encode();
            $statusResponse = array(
                'code' => 200
            );
        } catch (ApiException $e) {
            $statusResponse = $e->encode();
        }

        $response->setData(array(
            "status" => $statusResponse,
            "flight" => $flight
        ));

        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;

    }
}
