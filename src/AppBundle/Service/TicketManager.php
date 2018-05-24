<?php

namespace AppBundle\Service;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\TicketHistory;
use AppBundle\Entity\TicketStatus;
use Doctrine\ORM\EntityManagerInterface;

class TicketManager
{
    /**
     * @var EntityManagerInterface
     */
    public $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Ticket
     */
    public function createTicket()
    {
        $ticket = new Ticket();
        $ticket->generateRef($this->em->getRepository(Ticket::class)->getIncrement());

        $history = new TicketHistory();
        $history->setDate(new \DateTime());
        $history->setStatus($this->em->getRepository(TicketStatus::class)->findOneByCode(TicketStatus::OPEN_CODE));
        $ticket->addHistory($history);

        $ticket->setLastHistory($history);

        return $ticket;
    }


    /**
     * @param Ticket $ticket
     * @param TicketStatus|null $status
     * @return TicketHistory
     */
    public function createTicketHistory(Ticket $ticket, TicketStatus $status = null)
    {
        $ticketHistory = new TicketHistory();
        if($status) {
            $ticketHistory->setStatus($status);
        }

        $ticketHistory->setDate(new \DateTime());
        $ticketHistory->setTicket($ticket);

        return $ticketHistory;

    }


}