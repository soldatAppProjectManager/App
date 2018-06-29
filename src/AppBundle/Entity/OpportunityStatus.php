<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CodeTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * OpportunityStatus
 *
 * @ORM\Table(name="Opportunity_status")
 * @ORM\Entity()
 */
class OpportunityStatus
{
    const NCOURS_CODE = 1;

    const ECHU_CODE = 5;

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
     * @return StatutTicket
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

    public function __toString()
    {
     return $this->getLabel();
    }
}
