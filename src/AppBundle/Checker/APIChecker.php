<?php

namespace AppBundle\Checker;

use AppBundle\Entity\Airport;
use AppBundle\Entity\Flight;
use AppBundle\Entity\Book;
use Doctrine\ORM\EntityManager;

use AppBundle\Repository\AirportRepository;
use AppBundle\Repository\FlightRepository;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use AppBundle\Exception\AlreadyEditedException;
use AppBundle\Exception\NotFoundException;
use AppBundle\Exception\NotEnabledException;
use AppBundle\Exception\AccessDeniedException;
use AppBundle\Exception\NotAcceptedException;

class APIChecker
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \SoapClient
     */
    private $client;

    public function __construct(EntityManager $em)
    {
        if (version_compare(PHP_VERSION, '7.0.0', '<'))
            ini_set("soap.wsdl_cache_enabled", "0");
        $this->em = $em;
        $this->client = new \SoapClient('http://flightxml.flightaware.com/soap/FlightXML2/wsdl', array(
            'trace' => true,
            'exceptions' => 0,
            'login' => 'michaelmadar',
            'password' => '89a7aaff38f7049d1e62ef97bf28f463ffc2bb08',
        ));
    }

    public function processBook(Book $book) {
        $result = $this->checkFlightOfBook($book);
        if (!$result["success"])
            return $result;
        if ($book->getService() == "TRA") {
            $result = $this->checkFlightTransitOfBook($book);
            if (!$result["success"])
                return $result;
        }
        $book->setAirport($book->getFlight()->getMainAirport());
        return array(
            "success" => true
        );
    }

    private function checkFlightTransitOfBook(Book &$book) {
        $flight = $this->checkFlightWithOaciCode($book->getFlighttransitOaci());
        if ($flight == null)
            return $this->flightNotFound($book->getFlightOaci());
        $flight = $this->loadFlight($book, $flight);
        $flight->setType("DEP");
        $book->setflighttransit($flight);
        return $this->success($flight);
    }

    private function checkFlightOfBook(Book &$book) {
        $flight = $this->checkFlightWithOaciCode($book->getFlightOaci());
        if ($flight == null)
            return $this->flightNotFound($book->getFlightOaci());
        $result = $this->loadFlight($book, $flight);
        if (!$result instanceof  Flight)
            return $result;
        if ($book->getService() == "TRA")
            $result->setType("ARR");
        else
            $result->setType($book->getService());
        $book->setFlight($result);
        return $this->success($result);
    }

    private function loadFlight(Book $book, \stdClass $flight) {
        /** @var FlightRepository $airRepo */
        $flightRepo = $this->em->getRepository("AppBundle:Flight");
        $bddFlight = $flightRepo->getWithCodeOACI($flight->ident);
        if ($bddFlight instanceof Flight)
            return $bddFlight;
        return $this->createFlight($book, $flight);
    }

    private function createFlight(Book $book, \stdClass $flight) {
        /** @var AirportRepository $airRepo */
        $airRepo = $this->em->getRepository("AppBundle:Airport");
        $originAirport = $airRepo->getWithCodeOACI($flight->origin);
        if (!$this->checkAirportSupport($book, true, $originAirport, false, $flight->origin))
            return $this->notSupportedAirport($flight->origin);
        $destinationAirport = $airRepo->getWithCodeOACI($flight->destination);
        if (!$this->checkAirportSupport($book, false, $destinationAirport, false, $flight->destination))
            return $this->notSupportedAirport($flight->destination);
        $entityFlight = new Flight();
        $entityFlight->setDepair($originAirport);
        $entityFlight->setArrair($destinationAirport);
        $entityFlight->setNumber($flight->ident);
        $entityFlight->setCompagny(null);
        $entityFlight->setRemoved(false);
        $entityFlight->setArrtime($flight->estimatedarrivaltime);
        $entityFlight->setDeptime($flight->filed_departuretime);
        return $entityFlight;
    }

    private function checkFlightWithOaciCode($code){
        $result = $this->client->FlightInfo(array("ident" => $code, "howMany" => 1, "offset" => 0));
        if ($result instanceof \SoapFault) {
            echo $result->faultstring;
            return null;
        }
        $flight = $result->FlightInfoResult->flights;
        if (empty($flight->origin))
            return null;
        return $flight;
    }

    private function checkAirportSupport(Book $book, $isOrigin, Airport &$airport = null, $isTransitFlight, $oaci_code) {
        if ($book->getService() == "DEP" && $isOrigin && ($airport == null || !$airport->getSelectable()))
            return false;
        elseif ($book->getService() == "ARR" && !$isOrigin && ($airport == null || !$airport->getSelectable()))
            return false;
        elseif ($book->getService() == "TRA") {
            if (!$isOrigin && !$isTransitFlight && ($airport == null || !$airport->getSelectable()))
                return false;
            elseif ($isOrigin && $isTransitFlight && ($airport == null || !$airport->getSelectable()))
                return false;
        }
        $airport = $this->loadAirportWithAPI($airport, $oaci_code);
        if (!$airport instanceof Airport)
            return false;
        return true;
    }

    private function loadAirportWithAPI(Airport $airport = null, $oaci_code) {
        if ($airport instanceof Airport)
            return $airport;
        $result = $this->client->AirportInfo(array("airportCode" => $oaci_code));
        if ($airport instanceof \SoapFault)
            return null;
        $airport = new Airport();
        $airport->setName($result->AirportInfoResult->name);
        $airport->setCodeOaci($oaci_code);
        $airport->setCodeAita($oaci_code);
        $airport->setRemoved(false);
        $airport->setSelectable(false);
        $airport->setCompagny(null);
        return $airport;
    }

    private function success(Flight $flight) {
        return array( "success" => true, "flight" => $flight);
    }

    private function flightNotFound($OACICode) {
        return array( "success" => false, "flash" => array("type" => "danger", "msg" => "Impossible to find flight with OACI code : " . $OACICode));
    }

    private function notSupportedAirport($OACICode) {
        return array( "success" => false, "flash" => array("type" => "danger", "msg" => "We don't yet support airport with OACI code : " . $OACICode));
    }
}