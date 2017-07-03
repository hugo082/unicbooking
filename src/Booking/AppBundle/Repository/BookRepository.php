<?php

namespace Booking\AppBundle\Repository;

/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLast(){
        $qb = $this->createQueryBuilder('b');

        $qb->select('b')
            ->andwhere('b.creation_date <= :limit_top')
            //->andwhere('b.archived = false')
            ->setParameter('limit_top', new \DateTime())
            ->andwhere('b.creation_date >= :limit_bottom')
            ->setParameter('limit_bottom', new \DateTime("-1 month"))
            ->orderBy('b.creation_date', 'DESC');

        return $qb
            ->getQuery()
            ->getResult();
    }
}