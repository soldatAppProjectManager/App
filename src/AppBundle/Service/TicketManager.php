<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class TicketManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


}