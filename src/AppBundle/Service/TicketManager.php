<?php

namespace AppBundle\Service;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\TicketHistory;
use AppBundle\Entity\TicketStatus;
use AppBundle\Search\TicketCriteria;
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
        if ($status) {
            $ticketHistory->setStatus($status);
        }

        $ticketHistory->setDate(new \DateTime());
        $ticketHistory->setTicket($ticket);

        return $ticketHistory;
    }

    /**
     * @param TicketCriteria $criteria
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findByCriteriaQueryBuilder(TicketCriteria $criteria)
    {
        $qb = $this->em->createQueryBuilder('t')
            ->from('AppBundle:Ticket', 't')
            ->innerJoin('t.histories', 'th')
            ->innerJoin('th.status', 'st')
            ->orderBy('st.code');

        if ($criteria->getUser()) {
            $qb->andWhere('t.createdBy = :user')
                ->setParameter('user', $criteria->getUser());
        }

        if ($criteria->getCustomer()) {
            $qb->andWhere('t.client = :customer')
                ->setParameter('customer', $criteria->getCustomer());
        }

        if (!empty($criteria->getStartDate())) {
            $qb->andWhere('t.createdAt > :startDate')
                ->setParameter('startDate', $criteria->getStartDate());
        }

        if (!empty($criteria->getEndDate())) {
            $qb->andWhere('t.createdAt < :endDate')
                ->setParameter('endDate', $criteria->getEndDate());
        }

        return $qb;
    }

    /**
     * @return array
     */
    public function getStatistics(TicketCriteria $criteria)
    {

        $qb = $this->findByCriteriaQueryBuilder($criteria);

        $statistics = $qb
            ->select('st.label, st.color, count(t.id) as nbr')
            ->groupBy('st.label, st.color')
            ->getQuery()
            ->getArrayResult();

        $result = [];
        $result['data'] = json_encode(array_column($statistics, 'nbr'));
        $result['colors'] = json_encode(array_column($statistics, 'color'));
        $result['labels'] = json_encode(array_column($statistics, 'label'));

        return $result;
    }


}