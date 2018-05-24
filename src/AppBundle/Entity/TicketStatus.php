<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CodeTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * TicketStatus
 *
 * @ORM\Table(name="ticket_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketStatusRepository")
 */
class TicketStatus
{

    Const OPEN_CODE = 1;

    use CodeTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=64)
     */
    private $label;


    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=16)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="TicketStatus")
     */
    private $nextStatus;


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
     * Set label
     *
     * @param string $label
     *
     * @return TicketStatus
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return TicketStatus
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * Set nextStatus
     *
     * @param \AppBundle\Entity\TicketStatus $nextStatus
     *
     * @return TicketStatus
     */
    public function setNextStatus(\AppBundle\Entity\TicketStatus $nextStatus = null)
    {
        $this->nextStatus = $nextStatus;

        return $this;
    }

    /**
     * Get nextStatus
     *
     * @return \AppBundle\Entity\TicketStatus
     */
    public function getNextStatus()
    {
        return $this->nextStatus;
    }
}
