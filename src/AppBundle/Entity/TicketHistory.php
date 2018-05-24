<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedByTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * TicketHistory
 *
 * @ORM\Table(name="ticket_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketHistoryRepository")
 */
class TicketHistory
{
    use CreatedByTrait,TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Ticket
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="histories")
     */
    private $ticket;

    /**
     * @var TicketStatus
     * @ORM\ManyToOne(targetEntity="TicketStatus")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetimetz", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;
    
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $affectedTo;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return TicketHistory
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return TicketHistory
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\TicketStatus $status
     *
     * @return TicketHistory
     */
    public function setStatus(\AppBundle\Entity\TicketStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\TicketStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set affectedTo
     *
     * @param \AppBundle\Entity\User $affectedTo
     *
     * @return TicketHistory
     */
    public function setAffectedTo(\AppBundle\Entity\User $affectedTo = null)
    {
        $this->affectedTo = $affectedTo;

        return $this;
    }

    /**
     * Get affectedTo
     *
     * @return \AppBundle\Entity\User
     */
    public function getAffectedTo()
    {
        return $this->affectedTo;
    }

    /**
     * Set ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return TicketHistory
     */
    public function setTicket(\AppBundle\Entity\Ticket $ticket = null)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \AppBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}
