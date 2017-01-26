<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Compagny;
use AppBundle\Form\CompagnyType;

class CompagnyController extends Controller
{
    /**
    * @Route("/admin/manage/compagnys", name="admin.manage.compagnys")
    */
    public function indexAction(Request $request)
    {
        $compagny = new Compagny();

        $compagnys = $this->getDoctrine()->getRepository('AppBundle:Compagny')->findAll();

        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($compagny);
            $em->flush();
            return $this->redirectToRoute('admin.manage.compagnys');
        }

        return $this->render('booking/manage/compagny.html.twig', array(
            'form' => $form->createView(),
            'entities' => $compagnys
        ));
    }

    /**
    * @Route("/admin/manage/compagnys/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.compagnys.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $compagny = $this->getDoctrine()->getRepository('AppBundle:Compagny')->find($id);
        if ($compagny) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($compagny);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.compagnys');
    }

}
