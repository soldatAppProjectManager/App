<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\UpdatedByTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Rfp
 *
 * @ORM\Table(name="rfp")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RfpRepository")
 */
class Rfp
{
    use CreatedByTrait,UpdatedByTrait,TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=64)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="text")
     */
    private $object;

    /**
     * @ORM\OneToMany(targetEntity="Lot", mappedBy="rfp", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $lots;

    /**
     * @var RfpModele
     * @ORM\ManyToOne(targetEntity="RfpModele")
     * @ORM\JoinColumn(name="rfp_modele_id", referencedColumnName="id")
     */
    private $modele;

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
     * @return Rfp
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
     * Set number
     *
     * @param string $number
     *
     * @return Rfp
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set object
     *
     * @param string $object
     *
     * @return Rfp
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lot
     *
     * @param \AppBundle\Entity\Lot $lot
     *
     * @return Rfp
     */
    public function addLot(\AppBundle\Entity\Lot $lot)
    {
        $lot->setRfp($this);
        $this->lots[] = $lot;

        return $this;
    }

    /**
     * Remove lot
     *
     * @param \AppBundle\Entity\Lot $lot
     */
    public function removeLot(\AppBundle\Entity\Lot $lot)
    {
        $this->lots->removeElement($lot);
    }

    /**
     * Get lots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLots()
    {
        return $this->lots;
    }

    /**
     * Set modele
     *
     * @param \AppBundle\Entity\RfpModele $modele
     *
     * @return Rfp
     */
    public function setModele(\AppBundle\Entity\RfpModele $modele = null)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return \AppBundle\Entity\RfpModele
     */
    public function getModele()
    {
        return $this->modele;
    }
}
