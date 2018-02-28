<?php

namespace AppBundle\Repository;

/**
 * Class AbstractRepository
 * @package AppBundle\Repository
 */
class AbstractRepository extends \Doctrine\ORM\EntityRepository
{
    public function count() {
        return $this->createQueryBuilder('d')->select('count(d)')->getQuery()->getSingleScalarResult();
    }
}
