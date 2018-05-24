<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class CurrencyCollector
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function update()
    {

        $monnaies = $this->em->getRepository('AppBundle:Monnaie')->findAll();

        foreach ($monnaies as $monnaie) {
            $monnaie->recupererTauxBkam();
        }
        $this->em->flush();
    }
}