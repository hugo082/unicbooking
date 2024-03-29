<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Book;
use Symfony\Bundle\TwigBundle\TwigEngine;

class SenderManager
{
    protected $mailer;
    protected $twig;
    private $adminemail = 'booking@unicvip.com';

    public function __construct($mailer, TwigEngine  $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendBookAnswer($book, $state) {
        if ($state == "ACC") {
            $this->sendConfirmation($book);
        } else {
            $this->sendRejection($book);
        }
    }

    public function sendEditionAnswer($book, $state) {
        if ($state == "ACC" || $state == "ACP") {
            $this->sendEditionConfirmation($book);
        } else {
            $this->sendEditionRejection($book);
        }
    }

    public function sendWaiting(Book $book, $agentcpy = true, $admincpy = true) {
        $state = array('code' => 'wait', 'key' => 'Waitlist');
        $sub = ' • Acknowledgment of receipt';
        if ($admincpy) $this->sendEmail($book, $this->adminemail, $state, $sub);
        if ($agentcpy) $this->sendEmail($book, $book->getAgent()->getEmail(), $state, $sub);
    }

    public function sendConfirmation(Book $book, $agentcpy = true, $admincpy = true) {
        $state = array('code' => 'conf', 'key' => 'Confirmed');
        $sub = ' • Confirmed';
        if ($admincpy) $this->sendEmail($book, $this->adminemail, $state, $sub);
        if ($agentcpy) $this->sendEmail($book, $book->getAgent()->getEmail(), $state, $sub);
    }

    public function sendRejection(Book $book, $agentcpy = true, $admincpy = true) {
        $state = array('code' => 'reje', 'key' => 'CANCELLED');
        $sub = ' • Rejection';
        if ($admincpy) $this->sendEmail($book, $this->adminemail, $state, $sub);
        if ($agentcpy) $this->sendEmail($book, $book->getAgent()->getEmail(), $state, $sub);
    }

    public function sendEditionConfirmation(Book $book, $agentcpy = true, $admincpy = true) {
        $state = array('code' => 'edit', 'key' => 'Modification confirmed');
        $sub = ' • Modification';
        if ($admincpy) $this->sendEmail($book, $this->adminemail, $state, $sub);
        if ($agentcpy) $this->sendEmail($book, $book->getAgent()->getEmail(), $state, $sub);
    }

    public function sendEditionRejection(Book $book, $agentcpy = true, $admincpy = true) {
        $state = array('code' => 'edit', 'key' => 'Modification rejected');
        $sub = ' • Modification';
        if ($admincpy) $this->sendEmail($book, $this->adminemail, $state, $sub);
        if ($agentcpy) $this->sendEmail($book, $book->getAgent()->getEmail(), $state, $sub);
    }


    private function sendEmail($book, $sendat, $state, $sub)
    {
        $message = \Swift_Message::newInstance()
        ->setSubject('Unic Webooking' . $sub)
        ->setFrom(array('admin@unicairport.com' => 'Unic Webooking'))
        ->setTo($sendat)
        ->setBody($this->twig->render('Emails/test2.html.twig', array(
            'book' => $book,
            'state' => $state
        )),'text/html');
        $this->mailer->send($message);
    }
}
