<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\GenerateRefTrait;
use AppBundle\Entity\Traits\UpdatedByTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    use CreatedByTrait, UpdatedByTrait, TimestampableEntity, GenerateRefTrait;

    const REF_FORMAT = '%s%s-%s';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Priority
     * @ORM\ManyToOne(targetEntity="Priority")
     */
    private $priority;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=64)
     */
    private $tel;

    /**
     * @var Priority
     * @ORM\ManyToOne(targetEntity="Serie")
     */
    private $serie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $affectedTo;

    /**
     * @ORM\ManyToOne(targetEntity="FileSd", cascade={"persist"})
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="TicketHistory", mappedBy="ticket", cascade={"persist"})
     */
    protected $histories;

    /**
     * @ORM\ManyToOne(targetEntity="TicketHistory", cascade={"persist"})
     * @ORM\JoinColumn(name="last_history_id", referencedColumnName="id")
     */
    private $lastHistory;

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
     * @return Ticket
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
     * Set objet
     *
     * @param string $objet
     *
     * @return Ticket
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set priority
     *
     * @param \AppBundle\Entity\Priority $priority
     *
     * @return Ticket
     */
    public function setPriority(\AppBundle\Entity\Priority $priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \AppBundle\Entity\Priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set serie
     *
     * @param \AppBundle\Entity\Serie $serie
     *
     * @return Ticket
     */
    public function setSerie(\AppBundle\Entity\Serie $serie = null)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return \AppBundle\Entity\Serie
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ticket
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Ticket
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Ticket
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set affectedTo
     *
     * @param \AppBundle\Entity\User $affectedTo
     *
     * @return Ticket
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
     * Set file
     *
     * @param \AppBundle\Entity\FileSd $file
     *
     * @return Ticket
     */
    public function setFile(\AppBundle\Entity\FileSd $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \AppBundle\Entity\FileSd
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->histories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * Add history
     *
     * @param \AppBundle\Entity\TicketHistory $history
     *
     * @return Ticket
     */
    public function addHistory(\AppBundle\Entity\TicketHistory $history)
    {
        if (!$this->histories->contains($history)) {
            $history->setTicket($this);
            $this->setLastHistory($history);
            $this->histories[] = $history;
        }

        return $this;
    }

    /**
     * Remove history
     *
     * @param \AppBundle\Entity\TicketHistory $history
     */
    public function removeHistory(\AppBundle\Entity\TicketHistory $history)
    {
        $this->histories->removeElement($history);
    }

    /**
     * Get histories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * Set lastHistory
     *
     * @param \AppBundle\Entity\TicketHistory $lastHistory
     *
     * @return Ticket
     */
    public function setLastHistory(\AppBundle\Entity\TicketHistory $lastHistory)
    {
        $this->lastHistory = $lastHistory;

        return $this;
    }

    /**
     * Get lastHistory
     *
     * @return \AppBundle\Entity\TicketHistory
     */
    public function getLastHistory()
    {
        if(empty($this->lastHistory) && !empty($this->getHistories())) {
            return $this->getHistories()->last();
        }

        return $this->lastHistory;
    }

    /**
     * @return TicketStatus|null
     */
    public function getNextStatus()
    {
        if (
            $this->getLastHistory() &&
            $this->getLastHistory()->getStatus() &&
            $this->getLastHistory()->getStatus()->getNextStatus()
        ) {
            return $this->getLastHistory()->getStatus()->getNextStatus();
        }

        return null;
    }
}
