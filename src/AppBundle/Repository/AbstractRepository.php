<?php

namespace AppBundle\Repository;

/**
 * Class AbstractRepository
 * @package AppBundle\Repository
 */
class AbstractRepository extends \Doctrine\ORM\EntityRepository
{
    public function getIncrement()
    {
        $query = $this->createQueryBuilder('e')
            ->select('MAX(e.uuid)+1')
            ->where('year(e.createdAt) = :currentYear')
            ->setParameter('currentYear', date('Y'))
            ->getQuery();

        $max1 = $query->getSingleScalarResult();

        return (int)($max1 === null ? 1 : $max1);
    }
}
