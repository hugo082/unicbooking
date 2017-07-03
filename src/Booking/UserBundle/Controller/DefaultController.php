<?php

namespace Booking\UserBundle\Controller;

use Booking\UserBundle\Entity\User;
use Booking\UserBundle\Form\RegistrationType;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/create")
     */
    public function indexAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));
            if ($exists instanceof User) {
                throw new HttpException(409, 'Email already taken');
            }
            $userManager->updateUser($user);
        }
        return $this->render('BookingUserBundle:Default:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
