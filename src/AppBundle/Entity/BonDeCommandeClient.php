<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * BonDeCommandeClient
 *
 * @ORM\Table(name="bon_de_commande_client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BonDeCommandeClientRepository")
 */
class BonDeCommandeClient extends AbstractDocumentClient
{

    /**
     * @var \Devis
     *
     * @ORM\ManyToOne(targetEntity="Devis",inversedBy="BonDeCommandes")
     * @ORM\JoinColumn(name="devis_id", referencedColumnName="id")
     */
    private $devis;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedereception", type="datetime")
     */
    private $datedereception;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datebondecommande", type="datetime")
     */
    private $datebondecommande;

    /**
     * @var \integer
     *
     * @ORM\Column(name="echeance", type="integer")
     */
    private $echeance;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroDeBonDeCommandeClient", type="string", length=255)
     */
    private $numeroDeBonDeCommandeClient;

    /**
     * @var string
     *
     * @ORM\Column(name="fichier", type="string", length=255, nullable = true)
     */
    private $fichier;

    /**
     * @var bool
     *
     * @ORM\Column(name="verrouille", type="boolean")
     */
    private $verrouille;


    /**
     * @ORM\OneToMany(targetEntity="BonDeCommandeFournisseur", mappedBy="bonDeCommandeFournisseur")
     */
    private $bonsDeCommandeFournisseur;

    /**
     * @ORM\OneToMany(targetEntity="Livraison", mappedBy="bonDeCommandeClient")
     */
    private $livraisons;

    public function getLivraisons()
    {
        return $this->livraisons;
    }

    /**
     * @param UploadedFile $fichier
     * @return $this
     */
    public function setFichier(UploadedFile $fichier)
    {


        $this->fichier = "BC-" .
            "-" . $this->getDevis()->getClient()->getNom() .
            "-" . $this->getNumeroDeBonDeCommandeClient() .
            '.' . $fichier->guessExtension();
        return $this;
    }

    public function __toString()
    {
        return $this->getDatedereception()->format('Y_m') . "-" . $this->getNumeroDeBonDeCommandeClient() . " - " . $this->getClient()->getNom();
    }

    /**
     * Set datedereception
     *
     * @param \DateTime $datedereception
     *
     * @return BonDeCommandeClient
     */
    public function setDatedereception($datedereception)
    {
        $this->datedereception = $datedereception;

        return $this;
    }

    /**
     * Get datedereception
     *
     * @return \DateTime
     */
    public function getDatedereception()
    {
        return $this->datedereception;
    }

    /**
     * Set datebondecommande
     *
     * @param \DateTime $datebondecommande
     *
     * @return BonDeCommandeClient
     */
    public function setDatebondecommande($datebondecommande)
    {
        $this->datebondecommande = $datebondecommande;

        return $this;
    }

    /**
     * Get datebondecommande
     *
     * @return \DateTime
     */
    public function getDatebondecommande()
    {
        return $this->datebondecommande;
    }

    /**
     * Set echeance
     *
     * @param integer $echeance
     *
     * @return BonDeCommandeClient
     */
    public function setEcheance($echeance)
    {
        $this->echeance = $echeance;

        return $this;
    }

    /**
     * Get echeance
     *
     * @return integer
     */
    public function getEcheance()
    {
        return $this->echeance;
    }

    /**
     * Set numeroDeBonDeCommandeClient
     *
     * @param string $numeroDeBonDeCommandeClient
     *
     * @return BonDeCommandeClient
     */
    public function setNumeroDeBonDeCommandeClient($numeroDeBonDeCommandeClient)
    {
        $this->numeroDeBonDeCommandeClient = $numeroDeBonDeCommandeClient;

        return $this;
    }

    /**
     * Get numeroDeBonDeCommandeClient
     *
     * @return string
     */
    public function getNumeroDeBonDeCommandeClient()
    {
        return $this->numeroDeBonDeCommandeClient;
    }

    /**
     * Get fichier
     *
     * @return string
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set verrouille
     *
     * @param boolean $verrouille
     *
     * @return BonDeCommandeClient
     */
    public function setVerrouille($verrouille)
    {
        $this->verrouille = $verrouille;

        return $this;
    }

    /**
     * Get verrouille
     *
     * @return boolean
     */
    public function getVerrouille()
    {
        return $this->verrouille;
    }

    /**
     * Set devis
     *
     * @param \AppBundle\Entity\Devis $devis
     *
     * @return BonDeCommandeClient
     */
    public function setDevis(\AppBundle\Entity\Devis $devis = null)
    {
        $this->devis = $devis;

        return $this;
    }

    /**
     * Get devis
     *
     * @return \AppBundle\Entity\Devis
     */
    public function getDevis()
    {
        return $this->devis;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->termes = new ArrayCollection();
        $this->bonsDeCommandeFournisseur = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set commercial
     *
     * @param \AppBundle\Entity\User $commercial
     *
     * @return BonDeCommandeClient
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

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return BonDeCommandeClient
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
     * Add terme
     *
     * @param \AppBundle\Entity\TermeCommercial $terme
     *
     * @return BonDeCommandeClient
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

        return $this->getFraisFinanciers() / $this->getTotalHT();
    }

    public function getTauxMargeNette()
    {

        return $this->getMargeNette() / $this->getTotalHT();
    }


    public function getTotalTVA()
    {
        $totalTVA = 0;

        foreach ($this->getProduits() as $produit) {
            $totalTVA += $produit->getSoustotalHT() * $produit->getTauxTVA();
        }

        return round($totalTVA, 2);
    }

    public function getTotalTTC()
    {
        return $this->getTotalHT() + $this->getTotalTVA();
    }

    public function getMargeBrute()
    {
        $margeBrute = 0;

        foreach ($this->getProduits() as $produit) {
            $margeBrute += $produit->getMargeBrute($this->getDevis()->getTauxFinancementTresorerie());
        }

        return round($margeBrute, 2);
    }


    public function getTotalPrixRevient()
    {
        $prixRevient = 0;

        /** @var ProduitBC $produit */
        foreach ($this->getProduits() as $produit) {
            $prixRevient += $produit->getTotalPrixDeRevient();
        }

        return round($prixRevient, 2);
    }

    public function getFraisFinanciers()
    {
        $FraisFinanciers = 0;

        foreach ($this->getProduits() as $produit) {
            $FraisFinanciers += $produit->getFraisFinanciers($this->getDevis()->getTauxFinancementTresorerie());
        }

        return round($FraisFinanciers, 2);
    }

    public function getMarkUp()
    {
        return $this->getTotalPrixRevient() > 0 ? round(($this->getTotalHT() - $this->getTotalPrixRevient()) / $this->getTotalPrixRevient(), 4) : 0;
    }

    public function getTauxResultatDeChange()
    {
        return $this->getTotalHT() > 0 ? $this->getResultatDeChange() / $this->getTotalHT() : 0;
    }

    public function getResultatDeChange()
    {
        $resultatDeChange = 0;

        /** @var ProduitBC $produit */
        foreach ($this->getProduits() as $produit) {
            $resultatDeChange += $produit->getResultatDeChange();
        }
        return round($resultatDeChange, 4);
    }

    public function getTauxChargesExploitation()
    {
        return $this->getDevis()->getTravailminimum() + $this->getDevis()->getTravailLivraison()->getCharge() + $this->getDevis()->getTravailCommercial()->getCharge() + $this->getDevis()->getTravailAvantVente()->getCharge() + $this->getDevis()->getTravailImport()->getCharge();
    }

    public function getMargeNette()
    {
        return $this->getMargeBrute() - ($this->getTotalHT() * $this->getTauxChargesExploitation());
    }


    public function getRevenuParMetier()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getMetier()->getNom()])) {
                $revenu[$produit->getMetier()->getNom()]["revenu"] += $produit->getSousTotalHT();
            } else {
                $revenu[$produit->getMetier()->getNom()]["revenu"] = $produit->getSousTotalHT();
                $revenu[$produit->getMetier()->getNom()]["nom"] = $produit->getMetier()->getNom();
            }
        }
        return $revenu;
    }

    public function getRevenuParTypeProduit()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getTypeproduit()->getNom()])) {
                $revenu[$produit->getTypeproduit()->getNom()]["revenu"] += $produit->getSousTotalHT();
            } else {
                $revenu[$produit->getTypeproduit()->getNom()]["revenu"] = $produit->getSousTotalHT();
                $revenu[$produit->getTypeproduit()->getNom()]["nom"] = $produit->getTypeproduit()->getNom();
            }
        }
        return $revenu;
    }

    public function getMargeParMetier()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getMetier()->getNom()])) {
                $revenu[$produit->getMetier()->getNom()]["marge"] += $produit->getMargeBrute($this->getDevis()->getTauxFinancementTresorerie());
            } else {
                $revenu[$produit->getMetier()->getNom()]["marge"] = $produit->getMargeBrute($this->getDevis()->getTauxFinancementTresorerie());
                $revenu[$produit->getMetier()->getNom()]["nom"] = $produit->getMetier()->getNom();
            }
        }
        return $revenu;
    }

    public function getMargeParTypeProduit()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getTypeproduit()->getNom()])) {
                $revenu[$produit->getTypeproduit()->getNom()]["marge"] += $produit->getMargeBrute($this->getDevis()->getTauxFinancementTresorerie());
            } else {
                $revenu[$produit->getTypeproduit()->getNom()]["marge"] = $produit->getMargeBrute($this->getDevis()->getTauxFinancementTresorerie());
                $revenu[$produit->getTypeproduit()->getNom()]["nom"] = $produit->getTypeproduit()->getNom();
            }
        }
        return $revenu;
    }

    public function getRevenuParDevise()
    {
        foreach ($this->getProduits() as $produit) {

            if (isset($revenu[$produit->getDeviseachat()->getNom()])) {
                $revenu[$produit->getDeviseachat()->getNom()]["revenu"] += $produit->getSousTotalHT();
            } else {
                $revenu[$produit->getDeviseachat()->getNom()]["revenu"] = $produit->getSousTotalHT();
                $revenu[$produit->getDeviseachat()->getNom()]["nom"] = $produit->getDeviseachat()->getNom();
                $revenu[$produit->getDeviseachat()->getNom()]["taux"] = $produit->getTauxAchat();
            }
        }
        return $revenu;
    }

    public function estLivré()
    {
        foreach ($this->getProduits() as $produit) {
            if (!$produit->estLivré()) return false;
        }
        return true;
    }


    /**
     * Add bonsDeCommandeFournisseur
     *
     * @param \AppBundle\Entity\BonDeCommandeFournisseur $bonsDeCommandeFournisseur
     *
     * @return BonDeCommandeClient
     */
    public function addBonsDeCommandeFournisseur(\AppBundle\Entity\BonDeCommandeFournisseur $bonsDeCommandeFournisseur)
    {
        $this->bonsDeCommandeFournisseur[] = $bonsDeCommandeFournisseur;

        return $this;
    }

    /**
     * Remove bonsDeCommandeFournisseur
     *
     * @param \AppBundle\Entity\BonDeCommandeFournisseur $bonsDeCommandeFournisseur
     */
    public function removeBonsDeCommandeFournisseur(\AppBundle\Entity\BonDeCommandeFournisseur $bonsDeCommandeFournisseur)
    {
        $this->bonsDeCommandeFournisseur->removeElement($bonsDeCommandeFournisseur);
    }

    /**
     * Get bonsDeCommandeFournisseur
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBonsDeCommandeFournisseur()
    {
        return $this->bonsDeCommandeFournisseur;
    }

    /**
     * @return string
     */
    public function getClassTitle()
    {
        return "Bon de commande";
    }

    public function getRouteNameVoir()
    {
        return "BonDeCommandeClient_voir";
    }

    public function hasProductsInStock()
    {
        /** @var ProduitBC $produit */
        foreach ($this->getProduits() as $produit) {
            if (in_array($produit->getStatut()->getId(), [2, 8])) {
                return true;
            }
        }
    }



    /**
     * Add livraison
     *
     * @param \AppBundle\Entity\Livraison $livraison
     *
     * @return BonDeCommandeClient
     */
    public function addLivraison(\AppBundle\Entity\Livraison $livraison)
    {
        $this->livraisons[] = $livraison;

        return $this;
    }

    /**
     * Remove livraison
     *
     * @param \AppBundle\Entity\Livraison $livraison
     */
    public function removeLivraison(\AppBundle\Entity\Livraison $livraison)
    {
        $this->livraisons->removeElement($livraison);
    }
}
