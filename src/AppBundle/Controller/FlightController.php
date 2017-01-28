<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Flight;
use AppBundle\Form\FlightType;

class FlightController extends Controller
{
    /**
    * @Route("/admin/manage/flights", name="admin.manage.flights")
    */
    public function indexAction(Request $request)
    {
        $flight = new Flight();

        $flights = $this->getDoctrine()->getRepository('AppBundle:Flight')->getAllVisible();

        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();
            return $this->redirectToRoute('admin.manage.flights');
        }

        return $this->render('booking/manage/flight.html.twig', array(
            'form' => $form->createView(),
            'entities' => $flights
        ));
    }

    /**
    * @Route("/admin/manage/flights/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.flights.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $flight = $this->getDoctrine()->getRepository('AppBundle:Flight')->find($id);
        if ($flight) {
            $flight->setRemoved(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.flights');
    }

    /**
    * @Route("/admin/manage/flights/edit/{id}", requirements={"id" = "\d+"}, name="admin.manage.flights.edit")
    */
    public function editAction(Request $request, $id)
    {
        $flight = $this->getDoctrine()->getRepository('AppBundle:Flight')->find($id);
        if (!$flight) {
            return $this->redirectToRoute('admin.manage.flights');
        }
        $form = $this->createForm(FlightType::class, $flight);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($flight);
            $em->flush();
            return $this->redirectToRoute('admin.manage.flights');
        }
        return $this->render('booking/manage/flight.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
