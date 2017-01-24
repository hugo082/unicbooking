<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Book;
use AppBundle\Entity\SubBook;
use AppBundle\Form\BookType;
use AppBundle\Form\BookEmployeeType;
use AppBundle\Form\BookValidationType;

class BookController extends Controller
{
    /**
    * @Route("/booknow", name="booknow")
    */
    public function booknowAction(Request $request)
    {
        $user = $this->getUser();
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $book->setUser($user);
            $book->setPrice($this->getPrice($book));

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            $this->sendEmail($book, true);
            $this->sendEmail($book, false);
            return $this->redirectToRoute('book.enabled', array('id' => $book->getId()));
        }

        return $this->render('booking/form/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/book/edit/{id}", requirements={"id" = "\d+"}, name="book.edit")
    */
    public function editAction(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if (!$book || !$book->getEnabled()) {
            return $this->redirectToRoute('show', array('id' => $id));
        }
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->setPrice($this->getPrice($book));
            $book->setState("EDITED"); // A Remove
            // $subbook = new SubBook($book);
            // $subbook->setParent($book);

            $em = $this->getDoctrine()->getManager();
            //$em->persist($subbook);
            $em->persist($book);
            $em->flush();
            // $this->sendEmail($book, true);
            // $this->sendEmail($book, false);
            return $this->redirectToRoute('book.enabled', array('id' => $book->getId()));
        }

        return $this->render('booking/form/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/show/{id}", requirements={"id" = "\d+"}, name="show")
    */
    public function showAction(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if (!$book || !$book->getEnabled()) {
            return $this->render('booking/show/look.html.twig', array(
                'book_id_finded' => $id
            ));
        }

        $form = $this->createForm(BookEmployeeType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
        }

        return $this->render('booking/show/look.html.twig', array(
            'book' => $book,
            'form' => $form->createView()
        ));
    }

    /**
    * @Route("/book/enabled/{id}", requirements={"id" = "\d+"}, name="book.enabled")
    */
    public function enabledAction(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if (!$book || $book->getEnabled()) {
            return $this->redirectToRoute('show', array('id' => $id));
        }

        $form = $this->createForm(BookValidationType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('show', array('id' => $id));
        }

        return $this->render('booking/show/enabled.html.twig', array(
            'book' => $book,
            'form' => $form->createView()
        ));
    }

    private function sendEmail($book, $admin)
    {
        $message = \Swift_Message::newInstance()
        ->setSubject('Unic Webooking • Acknowledgment of receipt')
        ->setFrom(array('admin@unicairport.com' => 'Unic Webooking'))
        ->setTo(($admin) ? 'booking@unicvip.com' : $book->getUser()->getEmail())
        ->setBody($this->renderView('Emails/waiting.html.twig', array(
            'book' => $book,
            'user' => $book->getUser(),
            'is_admin' => $admin
        )),'text/html');
        $this->get('mailer')->send($message);
    }

    private function getPrice($book)
    {
        $cus = $book->getCustomers();
        foreach ($cus as $c) {
            $c->setBook($book);
        }

        $price = 0; $product = $book->getProduct();
        $nbP = $book->getAdultcus() + $book->getChildcus();
        $nbPPass = $product->getPassengers();
        $price += $product->getPrice();
        if ($nbP > $nbPPass){
            $supPrice = ($product->getCode() == 'GOL') ? 58 : 72;
            $price += ($nbP - $nbPPass) * $supPrice;
        }
        return $price + $book->getBags() * 10;
    }
}
