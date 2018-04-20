<?php

namespace AppBundle\Repository;

/**
 * OpportunityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OpportunityRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByStatusCode($code)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.status', 's')
            ->where('s.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult();
    }
}
