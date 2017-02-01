<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\SecurityEvents;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;
use FOS\UserBundle\Doctrine\UserManager;


class ChangePassListener implements EventSubscriberInterface {

    const CHANGE_PASS_ROLE = 'ROLE_FORCEPASSWORDCHANGE';

    private $context;
    private $token;
    private $router;
    private $session;
    private $dispatcher;
    private $usermanager;

    public function __construct(Router $router, AuthorizationChecker $context, TokenStorage $token, Session $session, TraceableEventDispatcher $dispatcher, UserManager $usermanager) {
        $this->context = $context;
        $this->token = $token;
        $this->router = $router;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->usermanager = $usermanager;
    }

    /**
    * {@inheritDoc}
    */
    public static function getSubscribedEvents() {
        return array(
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess'
        );
    }

    public function onLogin(InteractiveLoginEvent $event) {
        echo "Login Listener";
        if ($this->context->isGranted('IS_AUTHENTICATED_FULLY')) {
            $route_name = $event->getRequest()->get('_route');
            if ($route_name != 'fos_user_change_password' && $this->context->isGranted(self::CHANGE_PASS_ROLE)) {
                $response = new RedirectResponse($this->router->generate('fos_user_change_password'));
                $this->session->getFlashBag()->add('info', "Your password has expired. Please change it.");
                $this->dispatcher->addListener(KernelEvents::RESPONSE, function(FilterResponseEvent $event) {
                    $event->setResponse(new RedirectResponse($this->router->generate('fos_user_change_password')));
                });
            }
        }
    }

    public function onChangePasswordSuccess(FormEvent $event) {
        echo "ChangePasswordSuccess Listener";
        $user = $this->token->getToken()->getUser();
        $user->removeRole(self::CHANGE_PASS_ROLE);
        $this->usermanager->updateUser($user);
        $url = $this->router->generate('homepage');
        $event->setResponse(new RedirectResponse($url));
    }

}
