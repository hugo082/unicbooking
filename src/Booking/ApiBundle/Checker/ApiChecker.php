<?php

namespace Booking\ApiBundle\Checker;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Airport;
use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Flight;
use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Repository\AirportRepository;
use Booking\AppBundle\Repository\FlightRepository;
use Doctrine\ORM\EntityManager;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class ApiChecker
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
        $this->book->linkSubEntities();
        $this->computeFlights();
        // TODO: Check Support Airport
    }

    public function computeFlights() {
        /** @var Product $product */
        foreach ($this->book->getProducts() as $product) {
            if ($product->getProductType()->getService()->isAirport()) {
                /** @var FlightRepository $repo */
                $repo = $this->em->getRepository("BookingAppBundle:Flight");
                $product->getAirport()->computeFlightWithCurrentFlight($repo);
                $product->getAirport()->computeFlightTransitWithCurrentFlight($repo);
            }
        }
    }

    public function loadFlightWithRequest(Request $request): Flight
    {
        $flight_code = $request->get("flight_code");
        if ($flight_code == null)
            throw new ApiException("Bad Request", "Missing flight_code argument", 400);
        $flight = new Flight();
        $flight->getCodes()->setCode($flight_code, true);
        $flight = $this->loadFlightWithApi($flight);
        if (!$flight instanceof Flight)
            throw new ApiException("Error", "Unknow", 500);
        return $flight;
    }

    private function computeFlightWithFlightCodes(Flight $flight, $isTransitFlight = false) {
        if (!$this->book->isTransit())
            $flight->setType($this->book->getService());
        else
            $flight->setType($isTransitFlight ? "DEP" : "ARR");
        if (!$this->checkFlightSupport($flight, $isTransitFlight))
            throw new ApiException("Not Supported", "We don't yet support airport with code : " . $flight->getMainAirport()->getCodes()->getCode(), 503);
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
     * @param Flight $flightEntity
     * @return Flight
     * @throws ApiException
     */
    private function loadFlightWithApi(Flight $flightEntity){
        $result = $this->client->FlightInfo(array("ident" => $flightEntity->getCodes()->getFullIcao(), "howMany" => 1, "offset" => 0));
        if ($result instanceof \SoapFault)
            throw new ApiException("Flight Not Found", "Impossible to find flight with code : " . $flightEntity->getCodes()->getCode(), 404);
        $flight = $result->FlightInfoResult->flights;
        if (empty($flight->origin))
            throw new ApiException("Flight Not Found", "Impossible to find flight with code : " . $flightEntity->getCodes()->getCode(), 404);
        if (!$origin = $this->getAirportWithIcao($flight->origin))
            throw new ApiException("Airport Not Found", "Impossible to find airport with code : " . $flight->origin, 404);
        if (!$destination = $this->getAirportWithIcao($flight->destination))
            throw new ApiException("Airport Not Found", "Impossible to find airport with code : " . $flight->destination, 404);
        $flightEntity->setOrigin($origin);
        $flightEntity->setDestination($destination);
        $flightEntity->setArrivalTime($flight->estimatedarrivaltime);
        $flightEntity->setDepartureTime($flight->filed_departuretime);
        return $flightEntity;
    }

    /**
     * Get Airport from database. If doesn't exist, load from API.
     * @param $code
     * @return Airport|null
     */
    private function getAirportWithIcao($code) {
        /** @var AirportRepository $airRepo */
        $airRepo = $this->em->getRepository("BookingAppBundle:Airport");
        $airport = $airRepo->getWithCodeIcao($code);
        if ($airport instanceof Airport)
            return $airport;
        return $this->loadAirportWithAPI($code);
    }

    /**/
    private function setFlightToBook() {
        if ($this->book->getFlight()->getId() == null)
            throw new ApiException("danger", "Bad request", 400);
        $flight = $this->getFlightWithID($this->book->getFlight()->getId());
        $this->book->setFlight($flight);
    }

    /**/
    private function setFlightTransitToBook() {
        if ($this->book->getflighttransit() == null)
            return;
        if ($this->book->getflighttransit()->getId() == null)
            throw new ApiException("danger", "Bad request", 400);
        $flight = $this->getFlightWithID($this->book->getflighttransit()->getId());
        $this->book->setflighttransit($flight);
    }

    /**
     * @param $id
     * @return Flight
     */
    private function getFlightWithID($id) {
        /** @var FlightRepository $flightRepo */
        $flightRepo = $this->em->getRepository("BookingAppBundle:Flight");
        $flight = $flightRepo->find($id);
        if ($flight instanceof Flight)
            return $flight;
        throw new Exception("Invalid Flight");
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
        $airportEntity->setSupported(false);
        return $airportEntity;
    }
}