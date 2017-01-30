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
    * @Route("/admin/manage/airlines", name="admin.manage.compagnys")
    */
    public function indexAction(Request $request)
    {
        $compagny = new Compagny();

        $compagnys = $this->getDoctrine()->getRepository('AppBundle:Compagny')->getAllVisible();

        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $compagny = $this->uploadFile($compagny, $em);
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
    * @Route("/admin/manage/airlines/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.compagnys.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $compagny = $this->getDoctrine()->getRepository('AppBundle:Compagny')->find($id);
        if ($compagny) {
            $compagny->setRemoved(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($compagny);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.compagnys');
    }

    /**
    * @Route("/admin/manage/compagnys/edit/{id}", requirements={"id" = "\d+"}, name="admin.manage.compagnys.edit")
    */
    public function editAction(Request $request, $id)
    {
        $compagny = $this->getDoctrine()->getRepository('AppBundle:Compagny')->find($id);
        if (!$compagny) {
            return $this->redirectToRoute('admin.manage.compagnys');
        }
        $form = $this->createForm(CompagnyType::class, $compagny);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $compagny = $this->uploadFile($compagny, $em);
            echo "<br>". $compagny->getLogo() . " - " . $compagny->getName();
            $em->persist($compagny);
            $em->flush($compagny);
            return $this->redirectToRoute('admin.manage.compagnys');
        }
        return $this->render('booking/manage/compagny.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function uploadFile($cmp, $em) {
        $file = $cmp->getLogo();
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('logos_directory'),
            $fileName
        );
        echo "PATH : " . $this->getParameter('logos_directory') . $fileName;
        $cmp->setLogo($fileName);
        return $cmp;
    }

}
