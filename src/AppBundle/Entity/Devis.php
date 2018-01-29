<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \PHPExcel;
use \PHPExcel_IOFactory;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * Devis
 *
 * @ORM\Table(name="devis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DevisRepository")
 */
class Devis
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     */
    private $commercial;


    /**
     * @var \Entete
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Entete")
     * @ORM\JoinColumn(name="Entete_id", referencedColumnName="id")
     */
    private $introduction;

    /**
     * @var \Client
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="Client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var \TravailLivraison
     * @ORM\ManyToOne(targetEntity="TravailLivraison")
     * @ORM\JoinColumn(name="TravailLivraison_id", referencedColumnName="id")
     */
    private $TravailLivraison;

    /**
     * @var \TravailAvantVente
     * @ORM\ManyToOne(targetEntity="TravailAvantVente")
     * @ORM\JoinColumn(name="TravailAvantVente_id", referencedColumnName="id")
     */
    private $TravailAvantVente;

    /**
     * @var \TravailCommercial
     * @ORM\ManyToOne(targetEntity="TravailCommercial")
     * @ORM\JoinColumn(name="TravailCommercial_id", referencedColumnName="id")
     */
    private $TravailCommercial;

    /**
     * @var \TravailImport
     * @ORM\ManyToOne(targetEntity="TravailImport")
     * @ORM\JoinColumn(name="TravailImport_id", referencedColumnName="id")
     */
    private $TravailImport;

    /**
     * @var \Monnaie
     * @ORM\ManyToOne(targetEntity="Monnaie")
     * @ORM\JoinColumn(name="devisedevente_id", referencedColumnName="id")
     */
    private $devisevente;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0,
     *      max = 100
     * )
     * @ORM\Column(name="tauxvente", type="float")
     */
    private $tauxVente;

    /**
     * @var float
     *
     * @ORM\Column(name="Travailminimum", type="float")
     */
    private $Travailminimum;

    /**
     * @var float
     *
     * @ORM\Column(name="CoutMoyenService", type="float")
     */
    private $CoutMoyenService;

    /**
     * @var float
     *
     * @ORM\Column(name="TauxFinancementTresorerie", type="float")
     */
    private $TauxFinancementTresorerie;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var int
     *
     * @ORM\Column(name="numversion", type="integer")
     */
    private $numversion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="datetime")
     */
    private $datecreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodification", type="datetime")
     */
    private $datemodification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemiseajourchange", type="datetime")
     */
    private $datemiseajourchange;

    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 180
     * )
     * @ORM\Column(name="validite", type="integer")
     */
    private $validite;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var numdemande
     * @ORM\Column(name="numdemande", type="string", length=255, nullable=true)
     */
    private $numdemande;

    /**
     * @ORM\OneToMany(targetEntity="ProduitFusion", mappedBy="devis", cascade={"persist"})
     */
    private $fusionProduits;

    /**
     * @ORM\OneToMany(targetEntity="ProduitDevis", mappedBy="devis", cascade={"persist"})
     * @OrderBy({"numero" = "ASC"})
     */
    private $produits;

    private $lignes;

    /**
     * Many Users have Many termes.
     * @ManyToMany(targetEntity="TermeCommercial", inversedBy="devis")
     * @JoinTable(name="devis_termes")
     */
    private $termes;

    /**
     * @var \destinataire
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="contact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $destinataire;

    /**
     * @var \piedDePage
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="piedDePage")
     * @ORM\JoinColumn(name="piedDePage_id", referencedColumnName="id")
     */
    private $piedDePage;

    /**
     * @ORM\OneToMany(targetEntity="BonDeCommandeClient", mappedBy="devis")
     *
     */
    private $BonDeCommandes;

    /**
     * @var \modeleDevis
     * @ORM\ManyToOne(targetEntity="modeleDevis")
     * @ORM\JoinColumn(name="modeleDevis_id", referencedColumnName="id")
     */
    private $modele;

    /**
     * @var bool
     * @Assert\Choice({true, false})
     * @ORM\Column(name="draft", type="boolean")
     */
    private $draft;

    public function __construct($numero, $DeviseVenteDefaut, $CoutMoyenService, $TauxFinancementTresorerie, $Travailminimum, $commercial)
    {
        $now = new DateTime("now");

        $this->produits = new ArrayCollection();
        $this->termes = new ArrayCollection();
        $this->BonDeCommandes = new ArrayCollection();

        $this->setNumversion(0);
        $this->setNumero($numero);
        $this->setDatecreation($now);
        $this->setDatemodification($now);
        $this->setDatemiseajourchange($now);
        $this->setValidite(15);
        $this->setTauxFinancementTresorerie($TauxFinancementTresorerie);
        $this->setTravailminimum($Travailminimum);
        $this->setDevisevente($DeviseVenteDefaut);
        $this->setTauxVente(1);
        $this->setCoutMoyenService($CoutMoyenService);
        $this->setCommercial($commercial);
        $this->setDraft(true);

    }


    function __clone()
    {

        $produits = $this->getProduits();
        $fusions = $this->getFusionProduits();

        $this->termes = $this->getTermes();
        $this->BonDeCommandes = new ArrayCollection();

        $CloneProduits = new ArrayCollection();
        $CloneFusions = new ArrayCollection();

        foreach ($produits as $produit) {
            $CloneProduit = clone $produit;
            $CloneProduit->setDevis($this);
            $CloneProduits->add($CloneProduit);
        }

        foreach ($fusions as $fusion) {
            $CloneFusion = clone $fusion;
            $CloneFusion->setDevis($this);
            $CloneFusions->add($CloneFusion);
        }

        $this->setDraft(true);
        $this->fusionProduits = $CloneFusions;
        $this->produits = $CloneProduits;
    }

    public function ProduitDevis($numero)
    {
        $produit = new ProduitDevis;
        $produit->setNumero($numero);
        $this->addProduit($produit);
        $produit->setDevis($this);
        return $produit;
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
     * Set numversion
     *
     * @param integer $numversion
     *
     * @return Devis
     */
    public function setNumversion($numversion)
    {
        $this->numversion = $numversion;

        return $this;
    }

    /**
     * Get numversion
     *
     * @return int
     */
    public function getNumversion()
    {
        return $this->numversion;
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     *
     * @return Devis
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set datemodification
     *
     * @param \DateTime $datemodification
     *
     * @return Devis
     */
    public function setDatemodification($datemodification)
    {
        $this->datemodification = $datemodification;

        return $this;
    }

    /**
     * Get datemodification
     *
     * @return \DateTime
     */
    public function getDatemodification()
    {
        return $this->datemodification;
    }

    /**
     * Set validite
     *
     * @param integer $validite
     *
     * @return Devis
     */
    public function setValidite($validite)
    {
        $this->validite = $validite;

        return $this;
    }

    /**
     * Get validite
     *
     * @return int
     */
    public function getValidite()
    {
        return $this->validite;
    }


    public function getTotalHT()
    {
        $totalHT = 0;
        /** @var ProduitDevis $produit */
        foreach ($this->produits as $produit) {
            if (!$produit->estFusionné() and !$produit->getOptionnel()) $totalHT += $produit->getSoustotalht();
        }

        /** @var ProduitFusion $produit */
        foreach ($this->getFusionProduits() as $produit) {
            if (!$produit->estVide() and !$produit->getOptionnel()) $totalHT += $produit->getSoustotalht();
        }

        return round($totalHT, 2);
    }


    public function getTotalHTOptions()
    {
        $totalHT = 0;

        //foreach ($this->produits as $produit) {if (!$produit->estFusionné() and $produit->getOptionnel()) $totalHT += $produit->getSoustotalht();}

        foreach ($this->getFusionProduits() as $produit) {
            if (!$produit->estVide() and $produit->getOptionnel()) $totalHT += $produit->getSoustotalht();
        }

        return round($totalHT, 2);
    }

    public function getTotalTVA()
    {
        $totalTVA = 0;

        foreach ($this->produits as $produit) {
            if (!$produit->estFusionné() and !$produit->getOptionnel()) $totalTVA += $produit->getSoustotalHT() * $produit->getTauxTVA();
        }


        // echo $totalTVA."<br>";

        foreach ($this->getFusionProduits() as $produit) {
            if (!$produit->estVide() and !$produit->getOptionnel()) $totalTVA += $produit->getTotalTVA();
        }

        return round($totalTVA, 2);
    }


    public function getTotalTVAOptions()
    {
        $totalTVA = 0;

        //foreach ($this->produits as $produit) {if (!$produit->estFusionné() and !$produit->getOptionnel()) $totalTVA += $produit->getSoustotalHT()*$produit->getTauxTVA();}

        // echo $totalTVA."<br>";

        foreach ($this->getFusionProduits() as $produit) {
            if (!$produit->estVide() and $produit->getOptionnel()) $totalTVA += $produit->getTotalTVA();
        }

        return round($totalTVA, 2);
    }

    public function getTotalTTC()
    {
        return $this->getTotalHT() + $this->getTotalTVA();
    }

    public function getTotalTTCOptions()
    {
        return $this->getTotalHTOptions() + $this->getTotalTVAOptions();
    }

    public function getMargeBrute()
    {
        $margeBrute = 0;

        foreach ($this->produits as $produit) {

            if ($produit->estFusionné()) {

                if (!$produit->getOptionnel()) $margeBrute += $produit->getProduitFusion()->getQuantite() * $produit->getMargeBrute($this->TauxFinancementTresorerie);
            } else {

                if (!$produit->getOptionnel()) {

                    $margeBrute += $produit->getMargeBrute($this->TauxFinancementTresorerie);
                }
            }
        }

        return round($margeBrute / $this->getDeviseVente()->getTauxAchat(), 2);
    }

    public function getTotalPrixRevient()
    {
        $prixRevient = 0;

        /** @var ProduitDevis $produit */
        foreach ($this->produits as $produit) {
            if ($produit->estFusionné()) {
                if (!$produit->getOptionnel()) $prixRevient += $produit->getProduitFusion()->getQuantite() * $produit->getTotalPrixDeRevient() / $this->getDeviseVente()->getTauxAchat();
            } else {
                if (!$produit->getOptionnel()) {
                    $prixRevient += $produit->getTotalPrixDeRevient() / $this->getDeviseVente()->getTauxAchat();
                    // echo "$produit : ".$produit->getNumero()." : ".$produit->getTotalPrixDeRevient()."<br>";
                }
            }
        }
        return round($prixRevient / $this->getDeviseVente()->getTauxAchat(), 2);
    }

    public function getFraisFinanciers()
    {
        $FraisFinanciers = 0;

        foreach ($this->produits as $produit) {
            if ($produit->estFusionné()) {
                if (!$produit->getOptionnel())
                    $FraisFinanciers += $produit->getProduitFusion()->getQuantite() * $produit->getFraisFinanciers($this->TauxFinancementTresorerie);
            } else
                    if (!$produit->getOptionnel()) $FraisFinanciers += $produit->getFraisFinanciers($this->TauxFinancementTresorerie);
        }
        return round($FraisFinanciers / $this->getDeviseVente()->getTauxAchat(), 2);
    }

    public function getMarkUp()
    {
        return round(($this->getTotalHT() - $this->getTotalPrixRevient()) / $this->getTotalPrixRevient(), 4);
    }

    public function getResultatDeChange()
    {
        $resultatDeChange = 0;

        foreach ($this->produits as $produit) {
            if ($produit->estFusionné() and !$produit->getOptionnel()) $resultatDeChange += $produit->getProduitFusion()->getQuantite() * $produit->getResultatDeChange();
            else if (!$produit->getOptionnel()) $resultatDeChange += $produit->getResultatDeChange();
        }

        $resultatDeChange += $this->getTotalTTC() - $this->getTotalTTC() * $this->getTauxVente();

        return round($resultatDeChange, 4);
    }

    public function changerMarge($marge)
    {
        foreach ($this->produits as $produit) {
            if ($produits->getTypeproduit()->getId() != 1)
                $produit->setMarge($marge);
        }
    }

    public function getTauxChargesExploitation()
    {
        return $this->getTravailminimum() + $this->getTravailLivraison()->getCharge() + $this->getTravailCommercial()->getCharge() + $this->getTravailAvantVente()->getCharge() + $this->getTravailImport()->getCharge();
    }

    public function getMargeNette()
    {
        return $this->getMargeBrute() - ($this->getTotalHT() * $this->getTauxChargesExploitation());
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitDevis $produit
     *
     * @return Devis
     */
    public function addProduit(\AppBundle\Entity\ProduitDevis $produit)
    {

        if ($this->produits->isEmpty()) {
            $produit->setNumero(1);
        } else {
            $produit->setNumero($this->produits->count());
            $this->produits[] = $produit;
        }
        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitDevis $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitDevis $produit)
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

    public function setProduits($produits)
    {
        return $this->produits = $produits;
    }

    /**
     * Set commercial
     *
     * @param \AppBundle\Entity\Commercial $commercial
     *
     * @return Devis
     */
    public function setCommercial(\AppBundle\Entity\User $commercial = null)
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * Get commercial
     *
     * @return \AppBundle\Entity\Commercial
     */
    public function getCommercial()
    {
        return $this->commercial;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Devis
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Devis
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     *
     * @return Devis
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set travailLivraison
     *
     * @param \AppBundle\Entity\TravailLivraison $travailLivraison
     *
     * @return Devis
     */
    public function setTravailLivraison(\AppBundle\Entity\TravailLivraison $travailLivraison = null)
    {
        $this->TravailLivraison = $travailLivraison;

        return $this;
    }

    /**
     * Get travailLivraison
     *
     * @return \AppBundle\Entity\TravailLivraison
     */
    public function getTravailLivraison()
    {
        return $this->TravailLivraison;
    }

    /**
     * Set travailAvantVente
     *
     * @param \AppBundle\Entity\TravailAvantVente $travailAvantVente
     *
     * @return Devis
     */
    public function setTravailAvantVente(\AppBundle\Entity\TravailAvantVente $travailAvantVente = null)
    {
        $this->TravailAvantVente = $travailAvantVente;

        return $this;
    }

    /**
     * Get travailAvantVente
     *
     * @return \AppBundle\Entity\TravailAvantVente
     */
    public function getTravailAvantVente()
    {
        return $this->TravailAvantVente;
    }

    /**
     * Set travailCommercial
     *
     * @param \AppBundle\Entity\TravailCommercial $travailCommercial
     *
     * @return Devis
     */
    public function setTravailCommercial(\AppBundle\Entity\TravailCommercial $travailCommercial = null)
    {
        $this->TravailCommercial = $travailCommercial;

        return $this;
    }

    /**
     * Get travailCommercial
     *
     * @return \AppBundle\Entity\TravailCommercial
     */
    public function getTravailCommercial()
    {
        return $this->TravailCommercial;
    }

    /**
     * Set travailImport
     *
     * @param \AppBundle\Entity\TravailImport $travailImport
     *
     * @return Devis
     */
    public function setTravailImport(\AppBundle\Entity\TravailImport $travailImport = null)
    {
        $this->TravailImport = $travailImport;

        return $this;
    }

    /**
     * Get travailImport
     *
     * @return \AppBundle\Entity\TravailImport
     */
    public function getTravailImport()
    {
        return $this->TravailImport;
    }

    /**
     * Set travailminimum
     *
     * @param float $travailminimum
     *
     * @return Devis
     */
    public function setTravailminimum($travailminimum)
    {
        $this->Travailminimum = $travailminimum;

        return $this;
    }

    /**
     * Get travailminimum
     *
     * @return float
     */
    public function getTravailminimum()
    {
        return $this->Travailminimum;
    }

    /**
     * Set tauxFinancementTresorerie
     *
     * @param float $tauxFinancementTresorerie
     *
     * @return Devis
     */
    public function setTauxFinancementTresorerie($tauxFinancementTresorerie)
    {
        $this->TauxFinancementTresorerie = $tauxFinancementTresorerie;

        return $this;
    }


    /**
     * Get tauxFinancementTresorerie
     *
     * @return float
     */
    public function getTauxFinancementTresorerie()
    {
        return $this->TauxFinancementTresorerie;
    }

    public function mettreAJourTauxAchat()
    {
        foreach ($this->getProduits() as $produit) {
            $produit->mettreAJourTauxAchat();
        }
    }


    /**
     * Set piedDePage
     *
     * @param \AppBundle\Entity\piedDePage $piedDePage
     *
     * @return Devis
     */
    public function setPiedDePage(\AppBundle\Entity\piedDePage $piedDePage = null)
    {
        $this->piedDePage = $piedDePage;

        return $this;
    }

    /**
     * Get piedDePage
     *
     * @return \AppBundle\Entity\piedDePage
     */
    public function getPiedDePage()
    {
        return $this->piedDePage;
    }


    /**
     * Set destinataire
     *
     * @param \AppBundle\Entity\contact $destinataire
     *
     * @return Devis
     */
    public function setDestinataire(\AppBundle\Entity\contact $destinataire = null)
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    /**
     * Get destinataire
     *
     * @return \AppBundle\Entity\contact
     */
    public function getDestinataire()
    {
        return $this->destinataire;
    }

    public function setNumeros()
    {
        $i = 0;
        foreach ($this->produits as $produit) {
            $produit->setNumero(++$i);
        }
    }

    public function setReferences()
    {
        foreach ($this->produits as $produit) {
            $produit->setReference();
        }
    }

    public function remonter($produit)
    {
        if (!$this->getProduits()->isEmpty() && !$this->estPremier($produit)) {

            $precedent = $this->precedent($produit);

            $produit->monter();
            $precedent->descendre();
        }
    }

    public function estDernier($produit)
    {
        if ($this->getProduits()->isEmpty()) {
            return false;
        } else {
            return $produit->getNumero() == $this->getProduits()->count();
        }
    }

    public function estPremier($produit)
    {
        if ($this->getProduits()->isEmpty()) {
            return false;
        } else {
            return $produit->getNumero() == 1;
        }
    }

    public function precedent($produit)
    {

        if (!$this->getProduits()->isEmpty() && !$this->estPremier($produit)) {
            foreach ($this->getProduits() as $precedent) {
                if ($precedent->getNumero() + 1 == $produit->getNumero()) {
                    return $precedent;
                }
            }
        }
        return null;

    }

    public function getProduitSuivant($produit)
    {

        if (!$this->getProduits()->isEmpty() && !$this->estDernier($produit)) {
            foreach ($this->getProduits() as $suivant) {
                if ($suivant->getNumero() - 1 == $produit->getNumero()) {
                    return $suivant;
                }
            }
        }
        return null;
    }

    public function __toString()
    {
        return $this->getReference() . " - " . str_replace("/", "-", $this->getTitre()) . " - " . $this->getClient()->getNom();
    }

    public function getReference()
    {
        return $this->getDatecreation()->format('Y_m') . "-" . $this->getNumero() . "V" . $this->getNumversion();
    }

    public function getTauxMargeBrute()
    {

        return $this->getMargeBrute() / $this->getTotalHT();
    }

    public function getValeurMarkUP()
    {

        return $this->getTotalPrixRevient() * $this->getMarkUp();
    }

    public function getValeurChargesDExploitation()
    {

        return $this->getTauxChargesExploitation() * $this->getTotalHT();
    }

    public function getTauxFraisFinanciers()
    {

        return $this->getFraisFinanciers() / ($this->getTotalHT() * $this->getTauxVente());
    }

    public function getTauxMargeNette()
    {

        return $this->getMargeNette() / $this->getTotalHT();
    }

    public function getCoefficientChange()
    {
        return $this->getTauxFinancementTresorerie() * $this->getValidite() / 365 + 0.0075;
    }

    public function remonterSuivants($produit)
    {

        if (!$this->estDernier($produit)) {
            $suivant = $this->getProduitSuivant($produit);
            $this->remonterSuivants($suivant);
            $suivant->monter();

        }
    }

    public function reinitialiserNumeros()
    {
        $numero = 1;
        foreach ($this->getProduits() as $produit) {
            $produit->setNumero($numero++);
        }
    }

    public function isLocked()
    {
        return $this->getBonDeCommandes() != null;
    }

    /**
     * Add terme
     *
     * @param \AppBundle\Entity\TermeCommercial $terme
     *
     * @return Devis
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
     * Set datemiseajourchange
     *
     * @param \DateTime $datemiseajourchange
     *
     * @return Devis
     */
    public function setDatemiseajourchange($datemiseajourchange)
    {
        $this->datemiseajourchange = $datemiseajourchange;

        return $this;
    }

    /**
     * Get datemiseajourchange
     *
     * @return \DateTime
     */
    public function getDatemiseajourchange()
    {
        return $this->datemiseajourchange;
    }

    public function createFileName()
    {
        return "Devis " . str_replace(":", "-", $this);
    }


    /**
     * Add bonDeCommande
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommande
     *
     * @return Devis
     */
    public function addBonDeCommande(\AppBundle\Entity\BonDeCommandeClient $bonDeCommande)
    {
        $this->BonDeCommandes[] = $bonDeCommande;

        return $this;
    }

    /**
     * Remove bonDeCommande
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommande
     */
    public function removeBonDeCommande(\AppBundle\Entity\BonDeCommandeClient $bonDeCommande)
    {
        $this->BonDeCommandes->removeElement($bonDeCommande);
    }

    /**
     * Get bonDeCommandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBonDeCommandes()
    {
        return $this->BonDeCommandes;
    }

    public function estCommande()
    {
        return !$this->BonDeCommandes->isEmpty();
    }

    /**
     * Set coutMoyenService
     *
     * @param float $coutMoyenService
     *
     * @return Devis
     */
    public function setCoutMoyenService($coutMoyenService)
    {
        $this->CoutMoyenService = $coutMoyenService;

        return $this;
    }

    /**
     * Get coutMoyenService
     *
     * @return float
     */
    public function getCoutMoyenService()
    {
        return $this->CoutMoyenService;
    }


    public function getRevenuParMetier()
    {
        foreach ($this->getProduits() as $produit) {

            $quantité = $produit->estFusionné() ? $produit->getProduitFusion()->getQuantite() : 1;

            if (isset($revenu[$produit->getMetier()->getNom()])) {

                $revenu[$produit->getMetier()->getNom()]["revenu"] += $quantité * $produit->getSousTotalHT();
                $revenu[$produit->getMetier()->getNom()]["articles"] += 1;
                $revenu[$produit->getMetier()->getNom()]["pieces"] += $quantité * $produit->getQuantite();
            } else {
                $revenu[$produit->getMetier()->getNom()]["revenu"] = $quantité * $produit->getSousTotalHT();
                $revenu[$produit->getMetier()->getNom()]["articles"] = 1;
                $revenu[$produit->getMetier()->getNom()]["pieces"] = $quantité * $produit->getQuantite();
                $revenu[$produit->getMetier()->getNom()]["nom"] = $produit->getMetier()->getNom();
            }
        }
        return $revenu;
    }

    public function getRevenuParTypeProduit()
    {
        foreach ($this->getProduits() as $produit) {
            $quantité = $produit->estFusionné() ? $produit->getProduitFusion()->getQuantite() : 1;
            if (isset($revenu[$produit->getTypeproduit()->getNom()])) {
                $revenu[$produit->getTypeproduit()->getNom()]["revenu"] += $quantité * $produit->getSousTotalHT();
                $revenu[$produit->getTypeproduit()->getNom()]["articles"] += 1;
                $revenu[$produit->getTypeproduit()->getNom()]["pieces"] += $quantité * $produit->getQuantite();
            } else {
                $revenu[$produit->getTypeproduit()->getNom()]["revenu"] = $quantité * $produit->getSousTotalHT();
                $revenu[$produit->getTypeproduit()->getNom()]["articles"] = 1;
                $revenu[$produit->getTypeproduit()->getNom()]["pieces"] = $quantité * $produit->getQuantite();
                $revenu[$produit->getTypeproduit()->getNom()]["nom"] = $produit->getTypeproduit()->getNom();
            }
        }
        return $revenu;
    }

    public function getMargeParMetier()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getMetier()->getNom()])) {
                $revenu[$produit->getMetier()->getNom()]["marge"] += $produit->getMargeBrute($this->getTauxFinancementTresorerie());
            } else {
                $revenu[$produit->getMetier()->getNom()]["marge"] = $produit->getMargeBrute($this->getTauxFinancementTresorerie());
                $revenu[$produit->getMetier()->getNom()]["nom"] = $produit->getMetier()->getNom();
            }
        }
        return $revenu;
    }

    public function getMargeParTypeProduit()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getTypeproduit()->getNom()])) {
                $revenu[$produit->getTypeproduit()->getNom()]["marge"] += $produit->getMargeBrute($this->getTauxFinancementTresorerie());
            } else {
                $revenu[$produit->getTypeproduit()->getNom()]["marge"] = $produit->getMargeBrute($this->getTauxFinancementTresorerie());
                $revenu[$produit->getTypeproduit()->getNom()]["nom"] = $produit->getTypeproduit()->getNom();
            }
        }
        return $revenu;
    }


    public function getAchatsParFournisseur()
    {
        foreach ($this->getProduits() as $produit) {
            $quantité = $produit->estFusionné() ? $produit->getProduitFusion()->getQuantite() : 1;
            if (isset($revenu[$produit->getFournisseur()->getNom()])) {
                $revenu[$produit->getFournisseur()->getNom()]["revenu"] += $quantité * $produit->getPrixachatht() * $produit->getQuantite();
                $revenu[$produit->getFournisseur()->getNom()]["articles"] += 1;
                $revenu[$produit->getFournisseur()->getNom()]["pieces"] += $quantité * $produit->getQuantite();
            } else {
                $revenu[$produit->getFournisseur()->getNom()]["revenu"] = $quantité * $produit->getPrixachatht() * $produit->getQuantite();
                $revenu[$produit->getFournisseur()->getNom()]["articles"] = 1;
                $revenu[$produit->getFournisseur()->getNom()]["pieces"] = $quantité * $produit->getQuantite();
                $revenu[$produit->getFournisseur()->getNom()]["nom"] = $produit->getFournisseur()->getNom();
                $revenu[$produit->getFournisseur()->getNom()]["devise"] = $produit->getDeviseachat()->getSymbol();
            }
        }
        return $revenu;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Devis
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }


    /**
     * Set devisevente
     *
     * @param \AppBundle\Entity\Monnaie $devisevente
     *
     * @return Devis
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
     * Set modele
     *
     * @param \AppBundle\Entity\modeleDevis $modele
     *
     * @return Devis
     */
    public function setModele(\AppBundle\Entity\modeleDevis $modele = null)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return \AppBundle\Entity\modeleDevis
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set numdemande
     *
     * @param string $numdemande
     *
     * @return Devis
     */
    public function setNumdemande($numdemande)
    {
        $this->numdemande = $numdemande;

        return $this;
    }

    /**
     * Get numdemande
     *
     * @return string
     */
    public function getNumdemande()
    {
        return $this->numdemande;
    }

    /**
     * Add fusionProduit
     *
     * @param \AppBundle\Entity\ProduitFusion $fusionProduit
     *
     * @return Devis
     */
    public function addFusionProduit(\AppBundle\Entity\ProduitFusion $fusionProduit)
    {
        $this->fusionProduits[] = $fusionProduit;

        return $this;
    }

    /**
     * Remove fusionProduit
     *
     * @param \AppBundle\Entity\ProduitFusion $fusionProduit
     */
    public function removeFusionProduit(\AppBundle\Entity\ProduitFusion $fusionProduit)
    {
        $this->fusionProduits->removeElement($fusionProduit);
    }

    /**
     * Get fusionProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFusionProduits()
    {
        return $this->fusionProduits;
    }

    /**
     * Set draft
     *
     * @param boolean $draft
     *
     * @return Devis
     */
    public function setDraft($draft)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft
     *
     * @return boolean
     */
    public function getDraft()
    {
        return $this->draft;
    }


    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    public function setLignes($lignes)
    {
        $this->lignes = $lignes;
        return $this->lignes;
    }

    /**
     * Set tauxVente
     *
     * @param float $tauxVente
     *
     * @return Devis
     */
    public function setTauxVente($tauxVente)
    {
        $this->tauxVente = $tauxVente;

        return $this;
    }

    /**
     * Get tauxVente
     *
     * @return float
     */
    public function getTauxVente()
    {
        return $this->tauxVente;
    }

    public function construireLignes()
    {


        foreach ($this->produits as $produit) {
            if (!$produit->estFusionné() and !$produit->getOptionnel()) {

                $ligne = new LigneDevis();
                $ligne->setDevis($this);
                $this->addLigne($ligne);
                $ligne->setProduitDevis($produit);
                echo "Construction d'une ligne : " . $ligne->getNumero() . "<br>";
            }
        }
        foreach ($this->fusionProduits as $produit) {
            if (!$produit->getOptionnel()) {
                $ligne = new LigneDevis();
                $ligne->setDevis($this);
                $this->addLigne($ligne);
                $ligne->setProduitFusion($produit);
                echo "Construction d'une ligne : " . $ligne->getNumero() . "<br>";
            }
        }
    }


}
