<?php

namespace AppBundle\Checker;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use AppBundle\Exception\AlreadyEditedException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Exception\NotEnabledException;
use AppBundle\Exception\AccessDeniedException;
use AppBundle\Exception\NotAcceptedException;

class BookManager
{
    private $em;
    private $context;
    private $token;
    private $subrepo;

    public function __construct(EntityManager $em, AuthorizationChecker $context, TokenStorage $token)
    {
        $this->em = $em;
        $this->context = $context;
        $this->token = $token;
        $this->subrepo = $em->getRepository('AppBundle:SubBook');
    }

    public function edit($book, $uid) {
        $this->base($book, $uid);
        if ($book->getState() != 'ACCEPTED') {
            throw new NotAcceptedException($book);
        }
        if ($this->subrepo->getLastEdit($book) != NULL) {
            throw new AlreadyEditedException($book);
        }
    }

    public function enabled($book, $uid) {
        $this->base($book, $uid, true);
    }

    public function show($book, $uid) {
        $this->base($book, $uid);
    }

    private function base($book, $uid, $bool = false) {
        if (!$book) {
            throw new NotFoundException($uid);
        }
        if ($book->getEnabled() == $bool) {
            throw new NotEnabledException($book);
        }
        if ($this->getUser() != $book->getUser() && !$this->context->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException($book);
        }
    }

    private function getUser()
    {
        return $this->token->getToken()->getUser();
    }
}
