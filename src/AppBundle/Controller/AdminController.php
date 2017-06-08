<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Entity\SubBook;
use AppBundle\Repository\SubBookRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\Employee;
use AppBundle\Form\EmployeeType;

class AdminController extends Controller
{
    /**
    * @Route("/admin", name="admin")
    */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $book = new Employee();
        $form = $this->createForm(EmployeeType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
        }

        $waitingusers = $this->getDoctrine()->getRepository('AppBundle:User')->getWaiting();

        return $this->render('booking/manage/index.html.twig', array(
            'form' => $form->createView(),
            'w_users' => $waitingusers
        ));
    }

    /**
    * @Route("/admin/answer/user/{id}/{state}", name="admin.answer.user")
    */
    public function answerUserAction(Request $request, $id, $state)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        $em = $this->getDoctrine()->getManager();
        if ($state == "ACC") {
            $user->setEnabled(true);
            $em->persist($user);
        } else {
            $em->remove($user);
        }
        $em->flush();
        return $this->redirectToRoute('admin');
    }

    /**
    * @Route("/admin/answer/book/{id}/{state}", name="admin.answer.book",
    *     requirements={
    *         "id": "[0-9a-fA-F]+",
    *         "state": "REJ|ACC"
    *     })
    */
    public function answerBookAction(Request $request, $id, $state)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByid($id);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $fullstate = ($state == "ACC") ? "ACCEPTED" : "REFUSED";
        $this->get('app.mailer')->sendBookAnswer($book, $state);

        $book->setState($fullstate);
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('show', array('id' => $book->getid()));
    }

    /**
    * @Route("/admin/answer/book/edit/{id}/{state}", name="admin.answer.book.edit",
    *     requirements={
    *         "id": "[0-9a-fA-F]+",
    *         "state": "REJ|ACC|ACP"
    *     })
    */
    public function answerBookEditAction(Request $request, $id, $state)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByid($id);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        /** @var SubBookRepository $subrepo */
        $subrepo = $this->getDoctrine()->getRepository('AppBundle:SubBook');
        /** @var SubBook $subbook */
        $subbook = $subrepo->getLastEdit($book);

        if ($state == "ACC" || $state == "ACP") {
            $subbook->setState("ACCEPTED");
            if ($state == "ACP") {
                $subbook->setCharged(true);
                $book->addToPrice(25);
            }
        } else {
            $subbook->setState("REFUSED");
            $book = $subbook->backChange($book);
            $em->persist($book);
        }

        $em->persist($subbook);
        $em->flush();
        $this->get('app.mailer')->sendEditionAnswer($book, $state);
        return $this->redirectToRoute('show', array('id' => $book->getid()));
    }

    /**
     * @Route("/admin/archive/book/{id}", name="admin.archive.book",
     *     requirements={
     *         "id": "[0-9a-fA-F]+"
     *     })
     */
    public function archiveBookAction(Request $request, $id)
    {
        /** @var Book $book */
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByid($id);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $book->setArchived(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        $this->addFlash("success", "Book " . $book->getId() . " have been archived.");
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/list/book", name="admin.list")
     */
    public function listBookAction(Request $request)
    {
        /** @var Book $book */
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->findAll();
        return $this->render('booking/manage/list.html.twig', array(
            'books' => $books
        ));
    }
}
