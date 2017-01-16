<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\Driver;
use AppBundle\Form\DriverType;

class AdminController extends Controller
{
    /**
    * @Route("/admin", name="admin")
    */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $book = new Driver();
        $form = $this->createForm(DriverType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
        }

        $waitingusers = $this->getDoctrine()->getRepository('AppBundle:User')->findByEnabled(false);

        return $this->render('booking/admin.html.twig', array(
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
    *         "id": "\d+",
    *         "state": "REJ|ACC"
    *     })
    */
    public function answerBookAction(Request $request, $id, $state)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $fullstate = ($state == "ACC") ? "ACCEPTED" : "REFUSED";
        $this->sendEmail($state, $book);
        $book->setState($fullstate);
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('show', array('id' => $book->getId()));
    }

    private function sendEmail($state, $book)
    {
        if ($state == "ACC") {
            $template = 'Emails/confirmation.html.twig';
            $subject = 'Expert Webooking • Confirmation';
        } else {
            $template = 'Emails/rejected.html.twig';
            $subject = 'Expert Webooking • Rejection';
        }
        $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('booking@experttravel.fr' => 'Expert Webooking'))
        ->setTo($book->getUser()->getEmail())
        ->setBody($this->renderView($template, array(
            'book' => $book,
            'user' => $book->getUser()
        )),'text/html');
        $this->get('mailer')->send($message);
    }
}
