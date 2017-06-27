<?php

namespace Booking\AppBundle\Controller;

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
        echo "DASHBOARD";

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

        echo "BOOKNOW <br>";
        if ($form->isSubmitted() && $form->isValid() && $book->isValid()) {
            $book->linkProducts();
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            echo "SAVED <br>";
        } else {
            echo "Submitted " . $form->isSubmitted() . "<br>";
            echo "Valid " . $form->isValid() . "<br>";
            echo "Custom Valid " . $book->isValid() . "<br>";
            echo "Errors " . $form->getErrors() . "<br>";
        }
        return $this->render('dashboard/book/new.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/test/form")
     */
    public function testFormAction(Request $request)
    {
        $prod = new Product();
        $form = $this->createForm(ProductType::class, $prod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $prod->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();
            echo "SAVED";
        } else {
            echo "Submitted " . $form->isSubmitted() . "<br>";
            echo "Valid " . $form->isValid() . "<br>";
            echo "Custom Valid " . $prod->isValid() . "<br>";
            echo "Errors " . $form->getErrors() . "<br>";
        }

        return $this->render('dashboard/book/new.html.twig', array(
            "form" => $form->createView()
        ));
    }
}
