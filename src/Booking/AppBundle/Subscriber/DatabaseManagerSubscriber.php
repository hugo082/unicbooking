<?php

namespace Booking\AppBundle\Subscriber;

use Booking\AppBundle\Entity\Client;
use FQT\DBCoreManagerBundle\Event\ActionEvent;
use FQT\DBCoreManagerBundle\FQTDBCoreManagerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DatabaseManagerSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FQTDBCoreManagerEvents::ACTION_ADD_BEFORE => 'linkSubEntities',
            FQTDBCoreManagerEvents::ACTION_EDIT_BEFORE => 'linkSubEntities'
        );
    }

    public function linkSubEntities(ActionEvent $event) {
        $e = $event->getEntityObject();
        if ($e instanceof Client) {
            $e->linkContacts();
        }
    }
}