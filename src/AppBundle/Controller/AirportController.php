<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Airport;
use AppBundle\Form\AirportType;

class AirportController extends Controller
{
    /**
    * @Route("/admin/manage/airports", name="admin.manage.airports")
    */
    public function indexAction(Request $request)
    {
        $airport = new Airport();

        $airports = $this->getDoctrine()->getRepository('AppBundle:Airport')->getAllVisible();

        $form = $this->createForm(AirportType::class, $airport);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($airport);
            $em->flush();
            return $this->redirectToRoute('admin.manage.airports');
        }

        return $this->render('booking/manage/airport.html.twig', array(
            'form' => $form->createView(),
            'entities' => $airports
        ));
    }

    /**
    * @Route("/admin/manage/airports/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.airports.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $airport = $this->getDoctrine()->getRepository('AppBundle:Airport')->find($id);
        if ($airport) {
            $airport->setRemoved(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($airport);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.airports');
    }

    /**
    * @Route("/admin/manage/airports/edit/{id}", requirements={"id" = "\d+"}, name="admin.manage.airports.edit")
    */
    public function editAction(Request $request, $id)
    {
        $airport = $this->getDoctrine()->getRepository('AppBundle:Airport')->find($id);
        if (!$airport) {
            return $this->redirectToRoute('admin.manage.airport');
        }
        $form = $this->createForm(AirportType::class, $airport);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($airport);
            $em->flush();
            return $this->redirectToRoute('admin.manage.airports');
        }
        return $this->render('booking/manage/airport.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
