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

use AppBundle\Checker\BookManager as BookChecker;

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
            $book->updatePrice();
            $book->setCustomersParent();
            $cus = $book->getCustomers();

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book.enabled', array('id' => $book->getid()));
        }

        return $this->render('booking/form/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/book/edit/{id}", requirements={"id" = "[0-9a-fA-F]+"}, name="book.edit")
    */
    public function editAction(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByid($id);
        $bc = $this->get('app.checker.book')->edit($book, $id);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->updatePrice($this->getDoctrine());
            $book->setCustomersParent();

            $em = $this->getDoctrine()->getManager();
            $uow = $em->getUnitOfWork();
            $uow->computeChangeSets();
            $changeset = $uow->getEntityChangeSet($book);
            $subbook = new SubBook($book, $changeset);

            $em->persist($subbook);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('show', array('id' => $book->getid()));
        }

        return $this->render('booking/form/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/show/{id}", requirements={"id" = "[0-9a-fA-F]+"}, name="show")
    */
    public function showAction(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByid($id);
        $bc = $this->get('app.checker.book')->show($book, $id);

        $subrepo = $this->getDoctrine()->getRepository('AppBundle:SubBook');
        $subbook = $subrepo->getLastEdit($book);
        $activeSubbooks = $subrepo->getAll($book);

        $form = $this->createForm(BookEmployeeType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
        }

        return $this->render('booking/show/look.html.twig', array(
            'book' => $book,
            'subbook' => $subbook,
            'activeSubbooks' => $activeSubbooks,
            'form' => $form->createView()
        ));
    }

    /**
    * @Route("/book/enabled/{id}", requirements={"id" = "[0-9a-fA-F]+"}, name="book.enabled")
    */
    public function enabledAction(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByid($id);
        $bc = $this->get('app.checker.book')->enabled($book, $id);

        $form = $this->createForm(BookValidationType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            $this->get('app.mailer')->sendWaiting($book);
            return $this->redirectToRoute('show', array('id' => $id));
        }

        return $this->render('booking/show/enabled.html.twig', array(
            'book' => $book,
            'form' => $form->createView()
        ));
    }

    // private function bookErrorConvertor($error, $book, $id = 0) {
    //     if ($bc == 'NOT_FOUND') {
    //         throw $this->createNotFoundException('No user found for id '.$id);
    //     } else
    //
    // }

    // return $this->render('booking/show/look.html.twig', array(
    //     'book_id_finded' => $id
    // ));

}
