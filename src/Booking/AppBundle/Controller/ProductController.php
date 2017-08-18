<?php

namespace Booking\AppBundle\Controller;

use Booking\ApiBundle\Checker\ApiChecker;
use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\BookingAppBundle;
use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Form\Metadata\ProductSubMetadataType;
use Booking\AppBundle\Form\BookType;
use Booking\AppBundle\Form\Metadata\ProductType;
use Booking\AppBundle\Repository\BookRepository;
use Booking\AppBundle\Repository\Metadata\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{
    /**
     * @Route("/product")
     */
    public function indexAction(Request $request)
    {
        /** @var ProductRepository $repo */
        $repo = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product');
        $products = $repo->getOfUser($this->getUser());

        return $this->render('dashboard/book/list.html.twig', [
            'books' => null,
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/{id}")
     */
    public function showAction(Request $request, int $id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product')->find($id);
        if (!$product instanceof Product)
            throw new HttpException(404, "Product not found");

        $subcontractor = $product->getSubcontractor();
        $form = $this->createForm(ProductSubMetadataType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($subcontractor != $product->getSubcontractor())
                $product->computeDefaultEmployees();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute("booking_app_product_show", [
                "id" => $product->getId()
            ]);
        }

        return $this->render('dashboard/book/show/product.html.twig', array(
            "product" => $product,
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/product/edit/{id}")
     */
    public function editAction(Request $request, int $id)
    {
        /** @var Product $product */
        $product = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product')->find($id);

        $originalCustomers = $product->getCustomersCopy();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $product->isValid()) {
            /** @var ApiChecker $apiChecker */
            $apiChecker = $this->get('booking.api.checker');
            $apiChecker->processProduct($product);

            $em = $this->getDoctrine()->getManager();
            $product->removeNotFoundOriginalCustomers($em, $originalCustomers);
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute("booking_app_product_show", [
                "id" => $product->getId()
            ]);
        }

        $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');
        return $this->render('dashboard/book/product_form.html.twig', array(
            "form" => $form->createView(),
            'token' => $jwtManager->create($this->getUser())
        ));
    }
}
