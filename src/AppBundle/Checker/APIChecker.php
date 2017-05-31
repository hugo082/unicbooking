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

    /**
     * @var null|Book
     */
    private $book = null;

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
        $this->book = $book;
        $res = $this->computeFlightWithFlightCodes($this->book->getFlight());
        if (!$res instanceof Flight)
            return $res;
        if ($this->book->isTransit()) {
            $res = $this->computeFlightWithFlightCodes($this->book->getflighttransit(), true);
            if (!$res instanceof Flight)
                return $res;
        }
        $this->book->setAirport($this->book->getFlight()->getMainAirport());
        return array(
            "success" => true
        );
    }

    private function computeFlightWithFlightCodes(Flight $flight, $isTransitFlight = false) {
        $flight = $this->loadFlightWithApi($flight);
        if (!$flight instanceof Flight)
            return $flight;
        if (!$this->book->isTransit())
            $flight->setType($this->book->getService());
        else
            $flight->setType($isTransitFlight ? "DEP" : "ARR");
        if (!$this->checkFlightSupport($flight, $isTransitFlight))
            return $this->notSupportedAirport($flight->getMainAirport()->getCodes()->getCode());
        return $flight;
    }

    public function checkFlightSupport(Flight $flight, $isTransitFlight = false) {
        if ($this->book->isDeparture() && $flight->getDepair()->getSelectable())
            return true;
        if ($this->book->isArrival() && $flight->getArrair()->getSelectable())
            return true;
        if ($this->book->isTransit()) {
            if (!$isTransitFlight && $flight->getArrair()->getSelectable())
                return true;
            if ($isTransitFlight && $flight->getDepair()->getSelectable())
                return true;
        }
        return false;
    }

    /**
     * Load flight in API
     * @param Flight $flightEntity
     * @return Flight|array
     */
    private function loadFlightWithApi(Flight $flightEntity){
        $result = $this->client->FlightInfo(array("ident" => $flightEntity->getCodes()->getFullIcao(), "howMany" => 1, "offset" => 0));
        if ($result instanceof \SoapFault)
            return $this->flightNotFound($flightEntity->getCodes()->getFullIcao());
        $flight = $result->FlightInfoResult->flights;
        if (empty($flight->origin))
            return $this->flightNotFound($flightEntity->getCodes()->getFullIcao());
        if (!$oAirport = $this->getAirportWithIcao($flight->origin))
            return $this->airportNotFound($flight->origin);
        if (!$dAirport = $this->getAirportWithIcao($flight->destination))
            return $this->airportNotFound($flight->destination);
        $flightEntity->setDepair($oAirport);
        $flightEntity->setArrair($dAirport);
        $flightEntity->setCompagny(null);
        $flightEntity->setArrtime($flight->estimatedarrivaltime);
        $flightEntity->setDeptime($flight->filed_departuretime);
        return $flightEntity;
    }

    /**
     * Get Airport from database. If doesn't exist, load from API.
     * @param $code
     * @return Airport|null
     */
    private function getAirportWithIcao($code) {
        /** @var AirportRepository $airRepo */
        $airRepo = $this->em->getRepository("AppBundle:Airport");
        $airport = $airRepo->getWithCodeIcao($code);
        if ($airport instanceof Airport)
            return $airport;
        return $this->loadAirportWithAPI($code);
    }

    /**
     * Load Airport form API
     * @param $icao_code
     * @return Airport|null
     */
    private function loadAirportWithAPI($icao_code) {
        $airport = $this->client->AirportInfo(array("airportCode" => $icao_code));
        if ($airport instanceof \SoapFault)
            return null;
        $airportEntity = new Airport();
        $airportEntity->setName($airport->AirportInfoResult->name);
        $airportEntity->getCodes()->setIcao($icao_code, true);
        $airportEntity->setRemoved(false);
        $airportEntity->setSelectable(false);
        $airportEntity->setCompagny(null);
        return $airportEntity;
    }

    private function flightNotFound($OACICode) {
        return array( "success" => false, "flash" => array("type" => "danger", "msg" => "Impossible to find flight with code : " . $OACICode));
    }

    private function airportNotFound($OACICode) {
        return array( "success" => false, "flash" => array("type" => "danger", "msg" => "Impossible to find airport with code : " . $OACICode));
    }

    private function notSupportedAirport($OACICode) {
        return array( "success" => false, "flash" => array("type" => "danger", "msg" => "We don't yet support airport with code : " . $OACICode));
    }
}