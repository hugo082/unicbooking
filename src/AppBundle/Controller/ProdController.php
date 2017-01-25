<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;

class ProdController extends Controller
{
    /**
    * @Route("/admin/manage/products", name="admin.manage.prod")
    */
    public function indexAction(Request $request)
    {
        $prod = new Product();
        $prods = $this->getDoctrine()->getRepository('AppBundle:Product')->findAll();

        $form = $this->createForm(ProductType::class, $prod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
            return $this->render('booking/manage/product.html.twig', array(
                'form' => $form->createView(),
                'entities' => $prods,
                'edited' => true
            ));
        }

        return $this->render('booking/manage/product.html.twig', array(
            'form' => $form->createView(),
            'entities' => $prods
        ));
    }

    /**
    * @Route("/admin/manage/products/remove/{id}", requirements={"id" = "\d+"}, name="admin.manage.prod.remove")
    */
    public function removeAction(Request $request, $id)
    {
        $prod = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if ($prod) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($prod);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.prod');
    }

}
