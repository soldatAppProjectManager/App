<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProduitDevis
 *
 * @ORM\Table(name="produit_devis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitDevisRepository")
 */
class ProduitDevis extends AbstractProduit
{
    const DISCRIMINATOR = 'produitdevis';

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 20000000
     * )
     * @ORM\Column(name="prixachatht", type="float")
     */
    private $prixachatht;

    /**
     * @var float
     *
     * @ORM\Column(name="tauxTVA", type="float")
     */
    private $tauxTVA;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 50
     * )
     * @ORM\Column(name="fraisapproche", type="float")
     */
    private $fraisapproche;

    /**
     * @var bool
     * @Assert\Choice({true, false})
     * @ORM\Column(name="tauxSpecial", type="boolean")
     */
    private $tauxSpecial;

    /**
     * @var bool
     * @Assert\Choice({true, false})
     * @ORM\Column(name="cummulable", type="boolean")
     */
    private $cummulable;

    /**
     * @var \Monnaie
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Monnaie", cascade={"persist"})
     * @ORM\JoinColumn(name="Devise_Achat_id", referencedColumnName="id")
     */
    private $deviseachat;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 100
     * )
     * @ORM\Column(name="tauxAchat", type="float")
     */
    private $tauxAchat;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 20000000
     * )
     * @ORM\Column(name="marge", type="float")
     */
    private $marge;

    /**
     * @var \Fournisseur
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id")
     */
    private $fournisseur;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="referenceoffre", type="string", length=255)
     */
    private $referenceoffre;

    /**
     * @var string
     * @ORM\Column(name="referenceproduit", type="string", length=255,nullable=true)
     */
    private $referenceproduit;

    /**
     * @var \contact
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="contact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $commercial;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateoffre", type="datetime")
     */
    private $dateoffre;

    /**
     * @var \Metier
     *
     * @ORM\ManyToOne(targetEntity="Metier")
     * @ORM\JoinColumn(name="metier_id", referencedColumnName="id")
     */
    private $metier;

    /**
     * @var \TypeProduit
     *
     * @ORM\ManyToOne(targetEntity="TypeProduit")
     * @ORM\JoinColumn(name="typeproduit_id", referencedColumnName="id")
     */
    private $typeproduit;

    /**
     * @var \Monnaie
     *
     * @ORM\ManyToOne(targetEntity="Monnaie")
     * @ORM\JoinColumn(name="Devise_Vente_id", referencedColumnName="id")
     */
    private $devisevente;

    /**
     * Many produits have Many terms.
     * @ORM\ManyToMany(targetEntity="TermeCommercial", inversedBy="produits")
     * @ORM\JoinTable(name="produits_termes")
     * @ORM\OrderBy({"nom" = "ASC"})
     */
    private $termes;

    /**
     * Many produits have Many terms.
     * @ORM\ManyToMany(targetEntity="garantie", inversedBy="produits")
     * @ORM\JoinTable(name="produits_garanties")
     * @ORM\OrderBy({"nom" = "ASC"})
     */
    private $garanties;


    public function __construct()
    {
        $this->setTauxAchat(1);
        $this->setQuantite(1);
        $this->setMarge(0.12);
        $this->setFraisapproche(0);

        $this->termes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->garanties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getQuantite() . "X - " . $this->getDesignation() . " - " . $this->getFournisseur()->getNom();
    }


    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return ProduitDevis
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixachatht
     *
     * @param float $prixachatht
     *
     * @return ProduitDevis
     */
    public function setPrixachatht($prixachatht)
    {
        $this->prixachatht = $prixachatht;

        return $this;
    }

    /**
     * Get prixachatht
     *
     * @return float
     */
    public function getPrixachatht()
    {
        if ($this->typeproduit != NULL and $this->typeproduit->getId() == 1 and $this->marge != (1 - $this->getDocumentClient()->getCoutMoyenService())) {
            $this->marge = 1 - $this->getDocumentClient()->getCoutMoyenService();
            $this->setPrixachatht($this->getPrixachatht() * (1 - $this->marge));
            return $this->prixachatht;
        } else return $this->prixachatht;
    }

    /**
     * Set fraisapproche
     *
     * @param float $fraisapproche
     *
     * @return ProduitDevis
     */
    public function setFraisapproche($fraisapproche)
    {
        $this->fraisapproche = $fraisapproche;

        return $this;
    }

    /**
     * Get fraisapproche
     *
     * @return float
     */
    public function getFraisapproche()
    {
        return $this->fraisapproche;
    }


    /**
     * Set marge
     *
     * @param float $marge
     *
     * @return ProduitDevis
     */
    public function setMarge($marge)
    {

        if ($this->typeproduit != NULL and $this->typeproduit->getId() == 1 and $this->marge != (1 - $this->getDocumentClient()->getCoutMoyenService())) {
            $this->marge = 1 - $this->getDocumentClient()->getCoutMoyenService();
            $this->setPrixachatht($this->getPrixachatht() * (1 - $this->marge));

        } else $this->marge = round($marge, 8);

        return $this;
    }

    /**
     * Get marge
     *
     * @return float
     */
    public function getMarge()
    {
        if ($this->typeproduit != NULL and $this->typeproduit->getId() == 1 and $this->marge != (1 - $this->getDocumentClient()->getCoutMoyenService())) {
            $this->marge = 1 - $this->getDocumentClient()->getCoutMoyenService();
            $this->setPrixachatht($this->getPrixachatht() * (1 - $this->marge));
            return $this->marge;
        } else return $this->marge;
    }

    public function getPrixVenteHT()
    {
        $prixVente = ($this->prixachatht * (1 + $this->fraisapproche) * ($this->getTauxAchat())) / (1 - $this->marge) / $this->getDocumentClient()->getTauxVente();

        if ($this->getTypeproduit() && $this->getTypeproduit()->getPrecision() > 2) {
            return round($prixVente, $this->getTypeproduit()->getPrecision(), PHP_ROUND_HALF_UP);
        } else {
            return round($prixVente, 0, PHP_ROUND_HALF_UP);
        }
    }

    public function getMarkUp()
    {
        return round($this->marge / (1 - $this->marge), 4);
    }

    /**
     * Set referenceoffre
     *
     * @param string $referenceoffre
     *
     * @return ProduitDevis
     */
    public function setReferenceoffre($referenceoffre)
    {
        $this->referenceoffre = $referenceoffre;

        return $this;
    }

    /**
     * Get referenceoffre
     *
     * @return string
     */
    public function getReferenceoffre()
    {
        return $this->referenceoffre;
    }


    /**
     * Set metier
     *
     * @param \AppBundle\Entity\Metier $metier
     *
     * @return ProduitDevis
     */
    public function setMetier(\AppBundle\Entity\Metier $metier = null)
    {
        $this->metier = $metier;

        return $this;
    }

    /**
     * Get metier
     *
     * @return \AppBundle\Entity\Metier
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * Set typeproduit
     *
     * @param \AppBundle\Entity\TypeProduit $typeproduit
     *
     * @return ProduitDevis
     */
    public function setTypeproduit(\AppBundle\Entity\TypeProduit $typeproduit = null)
    {
        if ($typeproduit->getId() == 1) {
            $this->setMarge(1 - $this->getDocumentClient()->getCoutMoyenService());
            $this->setPrixachatht($this->getPrixachatht() * (1 - $this->getMarge()));
        }

        $this->typeproduit = $typeproduit;

        return $this;
    }

    /**
     * Get typeproduit
     *
     * @return \AppBundle\Entity\TypeProduit
     */
    public function getTypeproduit()
    {
        return $this->typeproduit;
    }

    /**
     * Set deviseachat
     *
     * @param \AppBundle\Entity\Monnaie $deviseachat
     *
     * @return ProduitDevis
     */
    public function setDeviseachat(\AppBundle\Entity\Monnaie $deviseachat = null)
    {
        $this->deviseachat = $deviseachat;

        return $this;
    }

    /**
     * Get deviseachat
     *
     * @return \AppBundle\Entity\Monnaie
     */
    public function getDeviseachat()
    {
        return $this->deviseachat;
    }

    /**
     * Set devisevente
     *
     * @param \AppBundle\Entity\Monnaie $devisevente
     *
     * @return ProduitDevis
     */
    public function setDevisevente(\AppBundle\Entity\Monnaie $devisevente = null)
    {
        $this->devisevente = $devisevente;

        return $this;
    }

    /**
     * Get devisevente
     *
     * @return \AppBundle\Entity\Monnaie
     */
    public function getDevisevente()
    {
        return $this->devisevente;
    }

    /**
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return ProduitDevis
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

    public function getSousTotalHT()
    {
        $precision = 2;
        if ($this->getTypeproduit()) {
            $precision = $this->getTypeproduit()->getPrecision();
        }
        return round($this->quantite * $this->getPrixVenteHT(), $precision);
    }

    public function getFraisFinanciers($TauxFinancementTresorerie)
    {
        $DelaiRecouvrement = $this->getDocumentClient()->getClient()->getDelaipaiementconstate();
        $TermeFournisseur = $this->fournisseur->getTermepaiement();

        return round((1 + $this->tauxTVA) * $this->getSousTotalHT() * $TauxFinancementTresorerie * ($DelaiRecouvrement - $TermeFournisseur) / 365, 2);
    }

    public function getTotalPrixDeRevient()
    {
        return round($this->prixachatht * (1 + $this->fraisapproche) * $this->quantite * $this->getTauxAchat(), 2);
    }

    public function getMargeBrute($TauxFinancementTresorerie)
    {
        return round($this->getSousTotalHT() - $this->getTotalPrixDeRevient() - $this->getFraisFinanciers($TauxFinancementTresorerie), 2);
    }


    public function getResultatDeChange()
    {
        return round(($this->prixachatht * (1 + $this->fraisapproche) * $this->quantite * $this->getTauxAchat() - $this->getTotalPrixDeRevient()),
            4);
    }

    /**
     * Set tauxTVA
     *
     * @param float $tauxTVA
     *
     * @return ProduitDevis
     */
    public function setTauxTVA($tauxTVA)
    {
        $this->tauxTVA = $tauxTVA;

        return $this;
    }

    /**
     * Get tauxTVA
     *
     * @return float
     */
    public function getTauxTVA()
    {
        return $this->tauxTVA;
    }

    /**
     * Set tauxAchat
     *
     * @param float $tauxAchat
     *
     * @return ProduitDevis
     */
    public function setTauxAchat($tauxAchat)
    {
        $this->tauxAchat = $tauxAchat;

        return $this;
    }

    /**
     * Get tauxAchat
     *
     * @return float
     */
    public function getTauxAchat()
    {
        return $this->tauxAchat;
    }

    public function mettreAJourTauxAchat()
    {
        $TauxFinancementTresorerie = $this->getDocumentClient()->getTauxFinancementTresorerie();

        $coefficientChange = $TauxFinancementTresorerie * ($this->getDocumentClient()->getValidite() + $this->getFournisseur()->getTermepaiement()) / 365;

        if ($this->getDeviseachat()->getCode() == "MAD") return $this->setTauxAchat($this->getDeviseachat()->getTauxAchat());
        else return $this->setTauxAchat((1 + $coefficientChange) * $this->getDeviseachat()->getTauxAchat());
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return ProduitDevis
     */
    public function setReference($reference = null)
    {
        $this->reference = $this->getDocumentClient()->getDatecreation()->format('Y/m') . "/" . $this->getFournisseur()->getNom() . "/" . $this->getDesignation() . "/" . $this->getDocumentClient()->getId();

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }


    public function monter()
    {
        if ($this->getOrdre() > 1) {
            $this->setOrdre($this->getOrdre() - 1);
        }
    }

    public function descendre()
    {
        $this->setOrdre($this->getOrdre() + 1);
    }

    /**
     * Add terme
     *
     * @param \AppBundle\Entity\TermeCommercial $terme
     *
     * @return ProduitDevis
     */
    public function addTerme(\AppBundle\Entity\TermeCommercial $terme)
    {
        $this->termes[] = $terme;

        return $this;
    }

    /**
     * Remove terme
     *
     * @param \AppBundle\Entity\TermeCommercial $terme
     */
    public function removeTerme(\AppBundle\Entity\TermeCommercial $terme)
    {
        $this->termes->removeElement($terme);
    }

    /**
     * Get termes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTermes()
    {
        return $this->termes;
    }

    /**
     * Add garanty
     *
     * @param \AppBundle\Entity\garantie $garanty
     *
     * @return ProduitDevis
     */
    public function addGaranty(\AppBundle\Entity\garantie $garanty)
    {
        $this->garanties[] = $garanty;

        return $this;
    }

    /**
     * Remove garanty
     *
     * @param \AppBundle\Entity\garantie $garanty
     */
    public function removeGaranty(\AppBundle\Entity\garantie $garanty)
    {
        $this->garanties->removeElement($garanty);
    }

    /**
     * Get garanties
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGaranties()
    {
        return $this->garanties;
    }

    /**
     * Set dateoffre
     *
     * @param \DateTime $dateoffre
     *
     * @return ProduitDevis
     */
    public function setDateoffre($dateoffre)
    {
        $this->dateoffre = $dateoffre;

        return $this;
    }

    /**
     * Get dateoffre
     *
     * @return \DateTime
     */
    public function getDateoffre()
    {
        return $this->dateoffre;
    }

    /**
     * Set commercial
     *
     * @param \AppBundle\Entity\contact $commercial
     *
     * @return ProduitDevis
     */
    public function setCommercial(\AppBundle\Entity\contact $commercial = null)
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * Get commercial
     *
     * @return \AppBundle\Entity\contact
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    // public function setReferenceAuto()
    // {
    //     $this->reference = $this->getDocumentClient()->getDatecreation()->format('Y/m')."/".$this->getFournisseur()->getNom(). "/". $this->getDesignation();

    //     return $this;
    // }


    /**
     * Set referenceproduit
     *
     * @param string $referenceproduit
     *
     * @return ProduitDevis
     */
    public function setReferenceproduit($referenceproduit)
    {
        $this->referenceproduit = $referenceproduit;

        return $this;
    }

    /**
     * Get referenceproduit
     *
     * @return string
     */
    public function getReferenceproduit()
    {
        return $this->referenceproduit;
    }


    /**
     * Set tauxSpecial
     *
     * @param boolean $tauxSpecial
     *
     * @return ProduitDevis
     */
    public function setTauxSpecial($tauxSpecial)
    {
        $this->tauxSpecial = $tauxSpecial;

        return $this;
    }

    /**
     * Get tauxSpecial
     *
     * @return boolean
     */
    public function getTauxSpecial()
    {
        return $this->tauxSpecial;
    }

    /**
     * Set cummulable
     *
     * @param boolean $cummulable
     *
     * @return ProduitDevis
     */
    public function setCummulable($cummulable)
    {
        $this->cummulable = $cummulable;

        return $this;
    }

    /**
     * Get cummulable
     *
     * @return boolean
     */
    public function getCummulable()
    {
        return $this->cummulable;
    }
}
