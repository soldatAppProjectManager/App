<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BonDeReception
 *
 * @ORM\Table(name="bon_de_reception")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BonDeReceptionRepository")
 */
class BonDeReception
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="BonDeCommandeFournisseur")
     */
    private $bonDeCommandeFournisseur;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

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
     * @return BonDeReception
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
     * Set bonDeCommandeFournisseur
     *
     * @param \AppBundle\Entity\BonDeCommandeFournisseur $bonDeCommandeFournisseur
     *
     * @return BonDeReception
     */
    public function setBonDeCommandeFournisseur(\AppBundle\Entity\BonDeCommandeFournisseur $bonDeCommandeFournisseur = null)
    {
        $this->bonDeCommandeFournisseur = $bonDeCommandeFournisseur;

        return $this;
    }

    /**
     * Get bonDeCommandeFournisseur
     *
     * @return \AppBundle\Entity\BonDeCommandeFournisseur
     */
    public function getBonDeCommandeFournisseur()
    {
        return $this->bonDeCommandeFournisseur;
    }
}
