<?php

namespace Booking\UserBundle\Manager;

use Booking\UserBundle\Entity\User;
use Booking\UserBundle\Form\RegistrationType;
use FQT\DBCoreManagerBundle\Core\Data;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserManager {

    /**
     * @var FormFactoryInterface
     */
    private $factory;
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->factory = $container->get('form.factory');
    }

    public function createUser(?User $user, Request $request) {
        $user = new User();
        $form = $this->factory->create(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));
            if ($exists instanceof User) {
                throw new HttpException(409, 'Email already taken');
            }
            $user->setEnabled(true);
            $userManager->updateUser($user);
            return new Data([
                    "success" => true,
                    "redirect" => true,
                    "flash" => [
                        [
                            "type" => "success",
                            "message" => "User created"
                        ]
                    ]
                ]
            );
        }
        return new Data([
                "success" => null,
                "form" => $form->createView()
            ]
        );
    }

}