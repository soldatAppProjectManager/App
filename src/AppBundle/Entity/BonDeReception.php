<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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

    /**
     * @ORM\OneToMany(targetEntity="ReceptionProduit", mappedBy="bonDeReception", cascade={"persist"})
     */
    private $receptionProduits;

    /**
     * @ORM\ManyToOne(targetEntity="FileSd", cascade={"persist"})
     */
    private $file;


    public function __construct()
    {
        $this->date = new \DateTime();
        $this->receptionProduits = new ArrayCollection();
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

    /**
     * Add receptionProduit
     *
     * @param \AppBundle\Entity\ReceptionProduit $receptionProduit
     *
     * @return BonDeReception
     */
    public function addReceptionProduit(\AppBundle\Entity\ReceptionProduit $receptionProduit)
    {
        if(!$this->receptionProduits->contains($receptionProduit)) {
            $receptionProduit->setBonDeReception($this);
            $this->receptionProduits[] = $receptionProduit;
        }

        return $this;
    }

    /**
     * Remove receptionProduit
     *
     * @param \AppBundle\Entity\ReceptionProduit $receptionProduit
     */
    public function removeReceptionProduit(\AppBundle\Entity\ReceptionProduit $receptionProduit)
    {
        $this->receptionProduits->removeElement($receptionProduit);
    }

    /**
     * Get receptionProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceptionProduits()
    {
        return $this->receptionProduits;
    }

    /**
     * Set file
     *
     * @param \AppBundle\Entity\FileSd $file
     *
     * @return BonDeReception
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
}
