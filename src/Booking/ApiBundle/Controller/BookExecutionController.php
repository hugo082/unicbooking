<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Serializer\ExecutionSerializer;
use Booking\AppBundle\Entity\Metadata\Execution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class BookExecutionController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/book/execution/{id}")
     */
    public function getExecutionAction(Request $request)
    {
        $execution = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Execution')->find($request->get('id'));
        if (!$execution instanceof Execution)
            return new JsonResponse(['message' => 'Execution not found'], Response::HTTP_NOT_FOUND);

        /** @var ExecutionSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.execution");
        return $serializer->serialize($execution);
    }

    /**
     * @Rest\View()
     * @Rest\Put("/book/execution/{id}")
     */
    public function updateExecutionAction(Request $request)
    {
        $execution = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Execution')->find($request->get('id'));
        if (!$execution instanceof Execution)
            return new JsonResponse(['message' => 'Execution not found'], Response::HTTP_NOT_FOUND);

        if (($step = $request->get("current_step", null)) === null || $step <= $execution->getCurrentStep())
            return new JsonResponse(['message' => 'Bad request, current_step not match.'], Response::HTTP_BAD_REQUEST);
        $execution->updateCurrentStep($step);

        $em = $this->getDoctrine()->getManager();
        $em->persist($execution);
        $em->flush();

        /** @var ExecutionSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.execution");
        return $serializer->serialize($execution);
    }
}
