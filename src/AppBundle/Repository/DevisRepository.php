<?php

namespace AppBundle\Repository;

/**
 * DevisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DevisRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRapportHebdomadaire($commercial){

        $date = new \DateTime('last monday');

         return $this->createQueryBuilder()
                        ->select('devis', 'commercial','client')
                        ->from('AppBundle:Devis', 'devis')
                        ->innerJoin('devis.commercial', 'commercial')
                        ->innerJoin('devis.client', 'client')
                        ->andWhere('devis.datecreation >= :min')
                        ->andWhere('devis.datecreation <= :max')
                        ->setParameters(array('min' => $date,'max' => new \DateTime("now")))
                        ->getQuery()->getResult();
    }

    public function getIncrement(){
         return $this   ->createQueryBuilder('devis')
                        ->select('MAX(devis.numero)+1')
                        ->getQuery()->getSingleScalarResult();
    }

    public function getIncrementVersion($NumeroDevis){
         return $this   ->createQueryBuilder('devis')
                        ->select('MAX(devis.numversion)+1')
                        ->where ('devis.numero = :num')
                        ->setParameters(array('num' => $NumeroDevis))
                        ->getQuery()->getSingleScalarResult();
    }


}
