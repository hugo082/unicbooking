<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;

class AdminController extends Controller
{
    /**
    * @Route("/admin", name="admin")
    */
    public function indexAction(Request $request)
    {
        return $this->render('booking/layout.html.twig');
    }

    /**
    * @Route("/admin/answer/{id}/{state}", name="admin.answer",
     *     requirements={
     *         "id": "\d+",
     *         "state": "REJ|ACC"
     *     })
    */
    public function answerAction(Request $request, $id, $state)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $fullstate = ($state == "ACC") ? "ACCEPTED" : "REFUSED";
        $book->setState($fullstate);
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('show', array('id' => $book->getId()));
    }
}
