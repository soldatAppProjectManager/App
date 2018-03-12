<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\GenerateRefTrait;
use Gedmo\Timestampable\Traits\TimestampableEntity;


/**
 * BonDeCommandeFournisseur
 *
 * @ORM\Table(name="bon_de_commande_fournisseur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BonDeCommandeFournisseurRepository")
 */
class BonDeCommandeFournisseur
{
    use CreatedByTrait, TimestampableEntity, GenerateRefTrait;

    const REF_FORMAT = 'BC%s%s-%s';
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Fournisseur
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id")
     */
    private $fournisseur;

    /**
     * @var Fournisseur
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="BonDeCommandeClient")
     * @ORM\JoinColumn(name="bcc_id", referencedColumnName="id")
     */
    private $bonDeCommandeClient;

    /**
     * @var ProduitBC
     * @ORM\ManyToMany(targetEntity="ProduitBC")
     */
    private $produits;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var ModeleBCF
     * @ORM\ManyToOne(targetEntity="ModeleBCF")
     * @ORM\JoinColumn(name="modele_bcf_id", referencedColumnName="id")
     */
    private $modele;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="commercial_id", referencedColumnName="id")
     */
    protected $commercial;

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
     * @return BonDeCommandeFournisseur
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
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return BonDeCommandeFournisseur
     */
    public function setFournisseur(\AppBundle\Entity\Fournisseur $fournisseur = null)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \AppBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * Set bonDeCommandeClient
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommandeClient
     *
     * @return BonDeCommandeFournisseur
     */
    public function setBonDeCommandeClient(\AppBundle\Entity\BonDeCommandeClient $bonDeCommandeClient = null)
    {
        $this->bonDeCommandeClient = $bonDeCommandeClient;

        return $this;
    }

    /**
     * Get bonDeCommandeClient
     *
     * @return \AppBundle\Entity\BonDeCommandeClient
     */
    public function getBonDeCommandeClient()
    {
        return $this->bonDeCommandeClient;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitBC $produit
     *
     * @return BonDeCommandeFournisseur
     */
    public function addProduit(\AppBundle\Entity\ProduitBC $produit)
    {
        if (!$this->produits->contains($produit))
            $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitBC $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitBC $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * @return float|int
     */
    public function getTotal()
    {
        $total = 0;
        /** @var ProduitBC $produit */
        foreach ($this->getProduits() as $produit) {
            $total += ($produit->getPrixachatht() * $produit->getQuantite());
        }

        return $total;
    }

    public function getTotalTVA()
    {
        $totalTVA = 0;
        /** @var ProduitBC $produit */
        foreach ($this->getProduits() as $produit) {
            $totalTVA += $produit->getQuantite() * $produit->getPrixachatht() * $produit->getTauxTVA();
        }

        return round($totalTVA, 2);
    }


    public function getTotalTTC()
    {
        return $this->getTotal() + $this->getTotalTVA();
    }

    /**
     * Set modele
     *
     * @param \AppBundle\Entity\ModeleBCF $modele
     *
     * @return BonDeCommandeFournisseur
     */
    public function setModele(\AppBundle\Entity\ModeleBCF $modele = null)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return \AppBundle\Entity\ModeleBCF
     */
    public function getModele()
    {
        return $this->modele;
    }

    public function isReceived()
    {
        if ($this->produits->count() == 0) {
            return false;
        }
        /** @var ProduitBC $produit */
        foreach ($this->getProduits() as $produit) {
            if ($produit->getStatut()->getId() !== 2) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set commercial
     *
     * @param \AppBundle\Entity\User $commercial
     *
     * @return BonDeCommandeFournisseur
     */
    public function setCommercial(\AppBundle\Entity\User $commercial = null)
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * Get commercial
     *
     * @return \AppBundle\Entity\User
     */
    public function getCommercial()
    {
        return $this->commercial;
    }
}
