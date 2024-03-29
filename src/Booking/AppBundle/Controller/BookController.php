<?php

namespace Booking\AppBundle\Controller;

use Booking\ApiBundle\Checker\ApiChecker;
use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\BookingAppBundle;
use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Form\BookTaxesType;
use Booking\AppBundle\Form\ProductSubMetadataType;
use Booking\AppBundle\Form\BookType;
use Booking\AppBundle\Form\Metadata\ProductType;
use Booking\AppBundle\Repository\BookRepository;
use Booking\AppBundle\Repository\Metadata\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $books = null; $products = null;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            /** @var BookRepository $repo */
            $repo = $this->getDoctrine()->getRepository('BookingAppBundle:Book');
            $books = $repo->getLast();
        }

        /** @var ProductRepository $repo */
        $repo = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product');
        $products = $repo->getOfUser($this->getUser());


        return $this->render('dashboard/book/list.html.twig', [
            'books' => $books,
            'products' => $products
        ]);
    }

    /**
     * @Route("/book/manage/new")
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $book->isValid()) {
            /** @var ApiChecker $apiChecker */
            $apiChecker = $this->get('booking.api.checker');
            try {
                $apiChecker->processBook($book);
                $book->linkSubEntities();
                $book->setHolder($this->getUser());

                $em = $this->getDoctrine()->getManager();
                $em->persist($book);
                $em->flush();
                return $this->redirectToRoute("booking_app_book_show", [
                    "id" => $book->getId()
                ]);
            } catch (ApiException $e) {
                $this->addFlash("error", $e->getMessage());
            }
        }

        $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');
        return $this->render('dashboard/book/new.html.twig', array(
            "form" => $form->createView(),
            'token' => $jwtManager->create($this->getUser())
        ));
    }

    /**
     * @Route("/book/manage/edit/{id}")
     */
    public function editAction(Request $request, int $id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository('BookingAppBundle:Book')->find($id);

        $originalProducts = [];
        /** @var Product $product */
        foreach ($book->getProducts() as $product) {
            $originalProducts[] = [ "product" => $product, "originalCustomers" => $product->getCustomersCopy() ];

        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $book->isValid()) {
            /** @var ApiChecker $apiChecker */
            $apiChecker = $this->get('booking.api.checker');
            $apiChecker->processBook($book);
            $book->linkSubEntities();

            $em = $this->getDoctrine()->getManager();
            $book->removeNotFoundOriginalProducts($em, $originalProducts);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("booking_app_book_show", [
                "id" => $book->getId()
            ]);
        }

        $jwtManager = $this->get('lexik_jwt_authentication.jwt_manager');
        return $this->render('dashboard/book/new.html.twig', array(
            "form" => $form->createView(),
            'token' => $jwtManager->create($this->getUser())
        ));
    }

    /**
     * @Route("/book/show/{id}")
     */
    public function showAction(Request $request, int $id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository("BookingAppBundle:Book")->find($id);

        return $this->render('dashboard/book/show/book.html.twig', array(
            "book" => $book
        ));
    }

    /**
     * @Route("/book/manage/archive/{id}/{billNumber}")
     */
    public function archiveAction(Request $request, int $id, string $billNumber)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository("BookingAppBundle:Book")->find($id);
        $book->setArchived(true);
        $book->setBillNumber($billNumber);
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute("booking_app_book_index");
    }

    /**
     * @Route("/book/manage/list")
     */
    public function listAction(Request $request)
    {
        /** @var Book[] $books */
        $books = $this->getDoctrine()->getRepository("BookingAppBundle:Book")->findAll();
        return $this->render('dashboard/book/archive.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/book/manage/taxes/{id}")
     */
    public function manageTaxesAction(Request $request, int $id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository("BookingAppBundle:Book")->find($id);
        $original = $book->getTaxesCopy();
        $form = $this->createForm(BookType::class, $book, [
            BookType::OPTION_TYPE => BookType::TYPE_TAXES
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->linkSubEntities();
            $em = $this->getDoctrine()->getManager();
            $book->removeNotFoundOriginalTaxes($em, $original);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("booking_app_book_show", [
                "id" => $book->getId()
            ]);
        }
        return $this->render('dashboard/book/taxes_form.html.twig', array(
            "form" => $form->createView(),
            "book" => $book
        ));
    }
}
