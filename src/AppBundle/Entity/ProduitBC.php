<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Parametres;
use AppBundle\Entity\Devis;
use AppBundle\Tools\devisExcel;
use AppBundle\Entity\ProduitDevis;

use DateTime;

/**
 * ProduitDevis
 *
 * @ORM\Table(name="produitbc")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitBCRepository")
 */
class ProduitBC extends AbstractProduit
{

    const DISCRIMINATOR = 'produitbc';

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     */
    private $reference;

    /**
     * @var float
     *
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
     *
     * @ORM\Column(name="fraisapproche", type="float")
     */
    private $fraisapproche;

    /**
     * @var Monnaie
     *
     * @ORM\ManyToOne(targetEntity="Monnaie", cascade={"persist"})
     * @ORM\JoinColumn(name="Devise_Achat_id", referencedColumnName="id")
     */
    private $deviseachat;

    /**
     * @var float
     *
     * @ORM\Column(name="tauxAchat", type="float")
     */
    private $tauxAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="referenceoffre", type="string", length=255)
     */
    private $referenceoffre;

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
     * @var \Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id")
     */
    private $fournisseur;

    /**
     * @var \Monnaie
     *
     * @ORM\ManyToOne(targetEntity="Monnaie", cascade={"persist"})
     * @ORM\JoinColumn(name="Devise_Vente_id", referencedColumnName="id")
     */
    private $devisevente;

    /**
     * @var \statutProduit
     *
     * @ORM\ManyToOne(targetEntity="statutProduit", cascade={"persist"})
     * @ORM\JoinColumn(name="statutProduit_id", referencedColumnName="id")
     */
    private $statut;

    /**
     * @var ProduitDevis
     *
     * @ORM\ManyToOne(targetEntity="ProduitDevis")
     * @ORM\JoinColumn(name="produit_devis_id", referencedColumnName="id")
     */
    private $produitDevis;

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

    public function getgaranties() {
        return $this->getProduitDevis()->getGaranties();
    }

    public function gettermes() {
        return $this->getProduitDevis()->getTermes();
    }

    public function deProduitDevis(ProduitDevis $produitdevis){
        $this->setQuantite($produitdevis->getQuantite());
        $this->setReference($produitdevis->getReference());
        $this->setDesignation($produitdevis->getDesignation());
        $this->setDescription($produitdevis->getDescription());
        $this->setFraisapproche($produitdevis->getFraisapproche());
        $this->setPrixachatht($produitdevis->getPrixachatht());
        $this->setTauxTVA($produitdevis->getTauxTVA());
        $this->setDeviseachat($produitdevis->getDeviseachat());
        $this->setTauxAchat($produitdevis->getTauxAchat());
        $this->setPrixVenteHT($produitdevis->getPrixVenteHT());
        $this->setReferenceoffre($produitdevis->getReferenceoffre());
        $this->setMetier($produitdevis->getMetier());
        $this->setTypeproduit($produitdevis->getTypeproduit());
        $this->setFournisseur($produitdevis->getFournisseur());
        $this->setDevisevente($produitdevis->getDevisevente());
        $this->setOptionnel($produitdevis->getOptionnel());
        $this->setProduitDevis($produitdevis);
        $this->setMarge($produitdevis->getMarge());
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
     * @ORM\OneToMany(targetEntity="ReceptionProduit", mappedBy="produit")
     */
    private $receptionProduits;

    /**
     * @ORM\OneToMany(targetEntity="LivraisonProduit", mappedBy="produit")
     */
    private $livraisonProduits;

    public function getReceptionProduits() {
        return $this->receptionProduits;
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

    public function getFraisFinanciers($TauxFinancementTresorerie)
    {
        $DelaiRecouvrement = $this->getDocumentClient()->getClient()->getDelaipaiementconstate();
        $TermeFournisseur = $this->fournisseur->getTermepaiement();

        return round(1.2*$this->getSousTotalHT()*$TauxFinancementTresorerie*($DelaiRecouvrement-$TermeFournisseur)/365,2);
    }

    public function getTotalPrixDeRevient()
    {
        return round($this->prixachatht*(1+$this->fraisapproche)*$this->deviseachat->getTauxAchat()*$this->quantite,2);
    }

    public function getMargeBrute($TauxFinancementTresorerie)
    {
        return round($this->getSousTotalHT() - $this->getTotalPrixDeRevient() - $this->getFraisFinanciers($TauxFinancementTresorerie)+$this->getResultatDeChange(),2);
    }

    public function getResultatDeChange()
    {
        // echo round($this->getTotalPrixDeRevient() - $this->prixachatht*(1+$this->fraisapproche)*$this->getTauxAchat()*$this->quantite,2) ."<br>";
        return round($this->getTotalPrixDeRevient() - $this->prixachatht*(1+$this->fraisapproche)*$this->getTauxAchat()*$this->quantite,2);
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
        $this->getDeviseachat()->recupererTauxBkam();
        return $this->setTauxAchat($this->getDeviseachat()->getTauxAchat());
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return ProduitDevis
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

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

    public function setReferenceAuto()
    {
        $this->reference = $this->getFournisseur()->getNom(). " - ". $this->getDesignation(). " - ". substr($this->getDescription(),0,30);
    }

    public function monter()
    {
        if ($this->getNumero() > 1) {$this->setNumero($this->getNumero()-1);} 
    }

    public function descendre()
    {
        $this->setNumero($this->getNumero()+1);
    }

    /**
     * Set statut
     *
     * @param \AppBundle\Entity\statutProduit $statut
     *
     * @return ProduitBC
     */
    public function setStatut(\AppBundle\Entity\statutProduit $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \AppBundle\Entity\statutProduit
     */
    public function getStatut()
    {
        return $this->statut;
    }  

    public function getMarkUp()
    {
        return round($this->getMarge()/(1-$this->getMarge()),5);
    }

    public function estLivré(){
        return $this->getStatut()->getId()==6;
    }

    /**
     * Set prixachatht
     *
     * @param float $prixachatht
     *
     * @return ProduitBC
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
        return $this->prixachatht;
    }

    /**
     * Set fraisapproche
     *
     * @param float $fraisapproche
     *
     * @return ProduitBC
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
     * Set produitDevis
     *
     * @param \AppBundle\Entity\ProduitDevis $produitDevis
     *
     * @return ProduitBC
     */
    public function setProduitDevis(\AppBundle\Entity\ProduitDevis $produitDevis = null)
    {
        $this->produitDevis = $produitDevis;

        return $this;
    }

    /**
     * Get produitDevis
     *
     * @return \AppBundle\Entity\ProduitDevis
     */
    public function getProduitDevis()
    {
        return $this->produitDevis;
    }

    /**
     * Set marge
     *
     * @param float $marge
     *
     * @return ProduitBC
     */
    public function setMarge($marge)
    {
        $this->marge = $marge;

        return $this;
    }

    /**
     * Get marge
     *
     * @return float
     */
    public function getMarge()
    {
        return $this->marge;
    }

    public function getQuantiteFournisseur()
    {
        $qte = $this->getQuantite();

        if($this->estFusionne()) {
            $qte = $qte * $this->getProduitFusion()->getQuantite();
        }

        return $qte;
    }


    public function __toString()
    {
        return $this->getDesignation();
    }

    public function getResteReception() {
        $reste = $this->getQuantite();
        /** @var ReceptionProduit $reception */
        foreach ($this->receptionProduits as $reception) {
            $reste= $reste - $reception->getQuantite();
        }

        return $reste;
    }

    public function getResteLivraison() {
        $reste = $this->getQuantite();
        /** @var LivraisonProduit $livraisonProduit */
        foreach ($this->livraisonProduits as $livraisonProduit) {
            $reste= $reste - $livraisonProduit->getQuantite();
        }

        return $reste;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->receptionProduits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add receptionProduit
     *
     * @param \AppBundle\Entity\ReceptionProduit $receptionProduit
     *
     * @return ProduitBC
     */
    public function addReceptionProduit(\AppBundle\Entity\ReceptionProduit $receptionProduit)
    {
        $this->receptionProduits[] = $receptionProduit;

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
     * Add livraisonProduit
     *
     * @param \AppBundle\Entity\LivraisonProduit $livraisonProduit
     *
     * @return ProduitBC
     */
    public function addLivraisonProduit(\AppBundle\Entity\LivraisonProduit $livraisonProduit)
    {
        $this->livraisonProduits[] = $livraisonProduit;

        return $this;
    }

    /**
     * Remove livraisonProduit
     *
     * @param \AppBundle\Entity\LivraisonProduit $livraisonProduit
     */
    public function removeLivraisonProduit(\AppBundle\Entity\LivraisonProduit $livraisonProduit)
    {
        $this->livraisonProduits->removeElement($livraisonProduit);
    }

    /**
     * Get livraisonProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivraisonProduits()
    {
        return $this->livraisonProduits;
    }


    public function getSousTotalHT()
    {
        return round($this->quantite * $this->getPrixVenteHT(), $this->getTypeproduit()->getPrecision());
    }

}
