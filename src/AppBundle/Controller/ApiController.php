<?php

namespace AppBundle\Controller;

use AppBundle\Checker\APIChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Exception\Api\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    /**
     * @Route("/data/api/flight", name="data.api.flight")
     */
    public function indexAction(Request $request)
    {
        $response = new JsonResponse();
        /** @var APIChecker $apiChecker */
        $apiChecker = $this->get('app.checker.api');

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