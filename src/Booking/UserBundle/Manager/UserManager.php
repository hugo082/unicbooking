<?php

namespace Booking\UserBundle\Manager;

use Booking\UserBundle\Entity\User;
use Booking\UserBundle\Form\EditType;
use Doctrine\ORM\EntityManager as ORMManager;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use FQT\DBCoreManagerBundle\Core\Data;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UserManager {

    /**
     * @var ORMManager
     */
    private $em;
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
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->container = $container;
    }

    public function createUser(?User $user, Request $request) {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                $this->container->get('logger')->info(
                    sprintf("New user registration: %s", $user)
                );
                return new Data([
                        "success" => true,
                        "redirect" => true,
                        "flash" => [
                            [
                                "type" => "success",
                                "message" => "User " . $user->getUsername() . " have been created."
                            ]
                        ]
                    ]
                );
            }
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);
            return new Data([
                    "success" => false,
                    "form" => $form->createView(),
                    "flash" => [
                        [
                            "type" => "error",
                            "message" => "Impossible to create this user."
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

    public function editUser(?User $user, Request $request) {
        $form = $this->factory->create(EditType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();
            return new Data([
                    "success" => true,
                    "redirect" => true,
                    "form" => $form->createView(),
                    "flash" => [
                        [
                            "type" => "success",
                            "message" => "User " . $user->getUsername() . " have been edited."
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

    public function disableUser(?User $user, Request $request) {
        /** @var $userManager UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        $user->setEnabled(!$user->isEnabled());
        $userManager->updateUser($user);
        return new Data([
                "success" => true,
                "redirect" => true,
                "flash" => [
                    [
                        "type" => "success",
                        "message" => "User " . $user->getUsername() . " have been " . ($user->isEnabled() ? "enabled." : "disabled.")
                    ]
                ]
            ]
        );
    }

}