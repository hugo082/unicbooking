<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;

class DefaultController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getLast($user);
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

        return $this->render('booking/manage.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

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
            $cus = $book->getCustomers();
            $book->setUser($user);
            $book->setState("WAITING");
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

            $major = $book->getBags() - (2 * $nbP);
            $price += ($major > 0) ? $major * 8 : 0;
            $book->setPrice($price);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('show', array('id' => $book->getId()));
        }

        return $this->render('booking/booknow.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * @Route("/show/{id}", requirements={"id" = "\d+"}, name="show")
    */
    public function showAction($id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);
        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        return $this->render('booking/show.html.twig', array(
            'book' => $book
        ));
    }

    /**
    * @Route("/email/{bool}", name="email")
    */
    public function emailAction($bool)
    {
        $user = $this->getUser();
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->find(18);
        if ($bool) {
            echo 'Send Action<br>';
            $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('hugo.fouquet@outlook.com')
            ->setTo('hugo-boss12@hotmail.fr')
            ->setBody($this->renderView('Emails/confirmation.html.twig', array(
                'book' => $book,
                'user' => $user
            )),'text/html');
            echo $this->get('mailer')->send($message);
        } else {
            echo 'Ignore Action';
        }

        return $this->render('Emails/confirmation.html.twig', array(
            'book' => $book,
            'user' => $user
        ));
    }
}
