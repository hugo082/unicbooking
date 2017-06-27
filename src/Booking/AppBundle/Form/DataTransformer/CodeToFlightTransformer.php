<?php
// src/AppBundle/Form/DataTransformer/IssueToNumberTransformer.php
namespace Booking\AppBundle\Form\DataTransformer;

use Booking\AppBundle\Entity\Flight;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CodeToFlightTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Flight|null $flight
     * @return string
     */
    public function transform($flight)
    {
        if (null === $flight)
            return '';
        return $flight->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $flight_id
     * @return Flight|null
     */
    public function reverseTransform($flight_id)
    {
        if (!$flight_id)
            return null;

        $flight = $this->em->getRepository('BookingAppBundle:Flight')->find($flight_id);
        return $flight;
    }
}