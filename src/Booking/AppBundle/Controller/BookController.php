<?php

namespace Booking\AppBundle\Controller;

use Booking\ApiBundle\Checker\ApiChecker;
use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\BookingAppBundle;
use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Form\BookType;
use Booking\AppBundle\Form\Metadata\ProductType;
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
        $user = $this->getUser();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $books = $this->getDoctrine()->getRepository('BookingAppBundle:Book')->getLast();
        } else {
            $books = $this->getDoctrine()->getRepository('BookingAppBundle:Book')->getOneMonthLast($user);
        }
        //$books = $user->getBooks(); FOR ALL

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('book_id', TextType::class, array(
                'label' => false,
                'attr' => array (
                    'placeholder' => 'manage.find.form.number'
                )
            ))->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute('show', array('id' => $data['book_id']));
        }

        return $this->render('dashboard/book/list.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/book/new")
     */
    public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $book->isValid()) {
            /** @var ApiChecker $apiChecker */
            $apiChecker = $this->get('booking.api.checker');
            $apiChecker->processBook($book);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("booking_app_book_show", [
                "id" => $book->getId()
            ]);
        }

        return $this->render('dashboard/book/new.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/book/edit/{id}")
     */
    public function editAction(Request $request, int $id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository('BookingAppBundle:Book')->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        echo "USE MY HistoricalBundle";

        if ($form->isSubmitted() && $form->isValid() && $book->isValid()) {
            /** @var ApiChecker $apiChecker */
            $apiChecker = $this->get('booking.api.checker');
            $apiChecker->processBook($book);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("booking_app_book_show", [
                "id" => $book->getId()
            ]);
        }

        return $this->render('dashboard/book/new.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/book/show/{id}")
     */
    public function showAction(Request $request, int $id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository("BookingAppBundle:Book")->find($id);
        return $this->render('dashboard/book/show.html.twig', array(
            "book" => $book
        ));
    }
}
