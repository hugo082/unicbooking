<?php

namespace Booking\AppBundle\Controller;

use Booking\ApiBundle\Checker\ApiChecker;
use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\BookingAppBundle;
use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Form\ProductEmployeeType;
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


        $form = $this->createForm(ProductEmployeeType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }

        return $this->render('dashboard/book/show/product.html.twig', array(
            "product" => $product,
            "form" => $form->createView()
        ));
    }
}
