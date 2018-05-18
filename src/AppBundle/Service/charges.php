<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class charges
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getMinimumCharges()
    {

        $query = $this->em->createQuery(
            'SELECT MIN(travail.charge)
                         FROM AppBundle:TravailImport travail'
        );

        $travail = floatval($query->getResult()[0][1]);

        $query = $this->em->createQuery(
            'SELECT MIN(travail.charge)
                         FROM AppBundle:TravailLivraison travail'
        );

        $travail += floatval($query->getResult()[0][1]);

        $query = $this->em->createQuery(
            'SELECT MIN(travail.charge)
                         FROM AppBundle:TravailCommercial travail'
        );

        $travail += floatval($query->getResult()[0][1]);

        $query = $this->em->createQuery(
            'SELECT MIN(travail.charge)
                         FROM AppBundle:TravailAvantVente travail'
        );

        $travail += floatval($query->getResult()[0][1]);

        $travail += $this->em->getRepository('AppBundle:Parametres')->find(3)->getValeur();

        return $travail;
    }

    public function getMaximumCharges()
    {

        $query = $this->em->createQuery(
            'SELECT MAX(travail.charge)
                         FROM AppBundle:TravailImport travail'
        );

        $travail = floatval($query->getResult()[0][1]);

        $query = $this->em->createQuery(
            'SELECT MAX(travail.charge)
                         FROM AppBundle:TravailLivraison travail'
        );

        $travail += floatval($query->getResult()[0][1]);

        $query = $this->em->createQuery(
            'SELECT MAX(travail.charge)
                         FROM AppBundle:TravailCommercial travail'
        );

        $travail += floatval($query->getResult()[0][1]);

        $query = $this->em->createQuery(
            'SELECT MAX(travail.charge)
                         FROM AppBundle:TravailAvantVente travail'
        );

        $travail += floatval($query->getResult()[0][1]);

        $travail += $this->em->getRepository('AppBundle:Parametres')->find(3)->getValeur();

        return $travail;
    }
}