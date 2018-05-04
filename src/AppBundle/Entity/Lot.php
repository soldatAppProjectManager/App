<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lot
 *
 * @ORM\Table(name="lot")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LotRepository")
 */
class Lot
{
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
     * @ORM\Column(name="number", type="string", length=8, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128)
     */
    private $title;

    /**
     * @var Rfp
     * @ORM\ManyToOne(targetEntity="Rfp", inversedBy="lots")
     */
    private $rfp;

    /**
     * @ORM\OneToMany(targetEntity="Devis", mappedBy="lot")
     */
    private $devis;


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
     * Set title
     *
     * @param string $title
     *
     * @return Lot
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set rfp
     *
     * @param \AppBundle\Entity\Rfp $rfp
     *
     * @return Lot
     */
    public function setRfp(\AppBundle\Entity\Rfp $rfp = null)
    {
        $this->rfp = $rfp;

        return $this;
    }

    /**
     * Get rfp
     *
     * @return \AppBundle\Entity\Rfp
     */
    public function getRfp()
    {
        return $this->rfp;
    }

    /**
     * @return ArrayCollection
     */
     public function getDevis()
    {
        return $this->devis;
    }



    public function __toString()
    {
        return $this->getRfp()->getNumber().': '. $this->getTitle();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->devis = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add devi
     *
     * @param \AppBundle\Entity\Devis $devi
     *
     * @return Lot
     */
    public function addDevi(\AppBundle\Entity\Devis $devi)
    {
        $this->devis[] = $devi;

        return $this;
    }

    /**
     * Remove devi
     *
     * @param \AppBundle\Entity\Devis $devi
     */
    public function removeDevi(\AppBundle\Entity\Devis $devi)
    {
        $this->devis->removeElement($devi);
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Lot
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
}
