<?php

namespace AppBundle\Repository;

/**
 * FlightRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FlightRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllVisible() {
        $qb = $this->createQueryBuilder('e');

        $qb
        ->select('e')
        ->where('e.removed = :rm')
        ->setParameter('rm', false );

        return $qb
        ->getQuery()
        ->getResult();
    }

    public function getWithCodeOACI($code_oaci) {
        $qb = $this->createQueryBuilder('e');

        $qb
            ->select('e')
            ->where('e.removed = :rm')
            ->setParameter('rm', false )
            ->andWhere('e.number = :oacicode')
            ->setParameter('oacicode', $code_oaci )
        ;

        return $qb
            ->getQuery()
            ->getOneOrNullResult();
    }
}
