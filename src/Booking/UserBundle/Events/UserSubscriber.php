<?php

namespace Booking\UserBundle\Events;

use Booking\UserBundle\Entity\User;
use FQT\DBCoreManagerBundle\Event\ActionEvent;
use FQT\DBCoreManagerBundle\FQTDBCoreManagerEvents;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserSubscriber implements EventSubscriberInterface {

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents() {
        return array(
            //FQTDBCoreManagerEvents::ACTION_ADD_BEFORE => 'beforeAdd'
        );
    }

    public function beforeAdd(ActionEvent $event) {
        $user = $event->getEntityObject();
        if ($user instanceof User) {
            $userManager = $this->container->get('fos_user.user_manager');
            $exists = $userManager->findUserBy(array('email' => $user->getEmail()));
            if ($exists instanceof User) {
                throw new HttpException(409, 'Email already taken');
            }
            $userManager->updateUser($user);
            $event->setExecuted(true);
        }
    }
}