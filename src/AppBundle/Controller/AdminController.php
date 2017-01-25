<?php

namespace AppBundle\Controller;

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

        $waitingusers = $this->getDoctrine()->getRepository('AppBundle:User')->findByEnabled(false);

        return $this->render('booking/manage/index.html.twig', array(
            'form' => $form->createView(),
            'w_users' => $waitingusers
        ));
    }

    /**
    * @Route("/admin/answer/user/{uid}/{state}", name="admin.answer.user")
    */
    public function answerUserAction(Request $request, $uid, $state)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneByUid($uid);
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for uid '.$uid
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
    * @Route("/admin/answer/book/{uid}/{state}", name="admin.answer.book",
    *     requirements={
    *         "uid": "\d+",
    *         "state": "REJ|ACC"
    *     })
    */
    public function answerBookAction(Request $request, $uid, $state)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByUid($uid);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for uid '.$uid
            );
        }
        $fullstate = ($state == "ACC") ? "ACCEPTED" : "REFUSED";
        $this->sendEmail($state, $book);
        $book->setState($fullstate);
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('show', array('uid' => $book->getUid()));
    }

    /**
    * @Route("/admin/answer/book/edit/{uid}/{state}", name="admin.answer.book.edit",
    *     requirements={
    *         "uid": "\d+",
    *         "state": "REJ|ACC|ACP"
    *     })
    */
    public function answerBookEditAction(Request $request, $uid, $state)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneByUid($uid);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for uid '.$uid
            );
        }
        $subrepo = $this->getDoctrine()->getRepository('AppBundle:SubBook');
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
        return $this->redirectToRoute('show', array('uid' => $book->getUid()));
    }

    private function sendEmail($state, $book)
    {
        if ($state == "ACC") {
            $template = 'Emails/confirmation.html.twig';
            $subject = 'Unic Webooking • Confirmation';
        } else {
            $template = 'Emails/rejected.html.twig';
            $subject = 'Unic Webooking • Rejection';
        }
        $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('admin@unicairport.com' => 'Unic Webooking'))
        ->setTo($book->getUser()->getEmail())
        ->setBody($this->renderView($template, array(
            'book' => $book,
            'user' => $book->getUser(),
            'is_admin' => false
        )),'text/html');
        $this->get('mailer')->send($message);
    }
}
