<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use AppBundle\Form\BookEmployeeType;

class DefaultController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getLast();
        } else {
            $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getOneMonthLast($user);
        }
        //$books = $user->getBooks(); FOR ALL

        $data = array();
        $form = $this->createFormBuilder($data)
        ->add('booknb', TextType::class, array('label' => false,
        'attr' => array (
            'placeholder' => 'manage.find.form.number'
        )
        ))->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            return $this->redirectToRoute('show', array('id' => $data['booknb']));
        }

        return $this->render('booking/index.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/email/{bool}", name="email")
    */
    public function emailAction($bool)
    {
        $user = $this->getUser();
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->getOneLast();
        if ($bool) {
            echo 'Send Action<br>';
            $this->get('app.mailer')->sendWaiting($book, true, false);
        } else {
            echo 'Ignore Action';
        }

        return $this->render('Emails/test2.html.twig', array(
            'book' => $book,
            'user' => $user,
            'state' => array('code' => 'conf', 'key'=>'Confirmed')
        ));
    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {
        return $this->render('dashboard/table/dynamic.html.twig');
    }
}
