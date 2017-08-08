<?php

namespace Booking\ApiBundle\Checker;

use Booking\ApiBundle\Exception\ApiException;
use Booking\AppBundle\Entity\Airport;
use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Flight;
use Booking\AppBundle\Entity\InternationalCodes\AirlinesCodes;
use Booking\AppBundle\Entity\InternationalCodes\InternationalCodes;
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
        foreach ($book->getProducts() as $product) {
            $this->processProduct($product);
        }
    }

    public function processProduct(Product $product) {
        $product->linkSubEntities();
        $this->computeFlights($product);
    }

    public function computeFlights(Product $product) {
        if ($product->getProductType()->getService()->isAirport()) {
            /** @var FlightRepository $repo */
            $repo = $this->em->getRepository("BookingAppBundle:Flight");
            $product->getAirport()->computeFlightWithCurrentFlight($repo);
            $product->getAirport()->computeFlightTransitWithCurrentFlight($repo);
            $product->getAirport()->checkAirportsSupport();
        }
    }

    public function loadFlightWithRequest(Request $request): Flight
    {
        $flight_code = $request->get("flight_code");
        if ($flight_code == null || !InternationalCodes::isValid($flight_code))
            throw new ApiException("Bad Request", "Missing or invalid flight_code argument", 400);
        $flight = new Flight();
        $flight->getCodes()->setCode($flight_code, true);
        $flight = $this->loadFlightWithApi($flight);
        if (!$flight instanceof Flight)
            throw new ApiException("Error", "Unknow", 500);
        return $flight;
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