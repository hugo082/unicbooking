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
        $prods = $this->getDoctrine()->getRepository('AppBundle:Product')->getAllVisible();

        $form = $this->createForm(ProductType::class, $prod);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$prod->getAdditionalPrice()) $prod->setAdditionalprice($prod->getPrice());
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
            return $this->redirectToRoute('admin.manage.prod');
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
            $prod->setRemoved(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
        }
        return $this->redirectToRoute('admin.manage.prod');
    }

    /**
    * @Route("/admin/manage/products/edit/{id}", requirements={"id" = "\d+"}, name="admin.manage.prod.edit")
    */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (!$product) {
            return $this->redirectToRoute('admin.manage.products');
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$product->getAdditionalPrice()) $product->setAdditionalprice($product->getPrice());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('admin.manage.prod');
        }
        return $this->render('booking/manage/product.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
