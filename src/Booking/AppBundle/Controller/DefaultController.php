<?php

namespace Booking\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        echo "DASHBOARD";
        return $this->render('BookingAppBundle:Default:index.html.twig');
    }
}
