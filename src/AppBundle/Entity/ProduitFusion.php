<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\OneToOne;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * ProduitFusion
 *
 * @ORM\Table(name="produit_fusion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitFusionRepository")
 */
class ProduitFusion
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
     * @var bool
     * 
     * @ORM\Column(name="optionnel", type="boolean")
     */
    private $optionnel;    

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="designation", type="string", length=255)
     */
    private $designation;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     * @Assert\Range(
     *      min = 0,
     *      max = 20000000
     * )
     * @ORM\Column(name="prixVenteHT", type="string", length=255)
     */
    private $prixVenteHT;

    /**
     * @var int
     * @Assert\Range(
     *      min = 1,
     *      max = 20000
     * )
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var \Devis
     *
     * @ORM\ManyToOne(targetEntity="Devis",inversedBy="fusionProduits")
     * @ORM\JoinColumn(name="Devis_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $devis;

    /**
    * @ORM\OneToMany(targetEntity="ProduitDevis", mappedBy="ProduitFusion", cascade={"persist"})
    * @OrderBy({"numero" = "ASC"})
    */
    private $produits;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;


    public function __toString()
    {
        return $this->getDesignation()." - ".$this->getDescription();
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
     * Set designation
     *
     * @param string $designation
     *
     * @return ProduitFusion
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProduitFusion
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set prixdevente
     *
     * @param string $prixVenteHT
     *
     * @return ProduitFusion
     */
    public function setPrixVenteHT($prixVenteHT)
    {
        $this->prixVenteHT = $prixVenteHT;

        return $this;
    }

    /**
     * Get prixdevente
     *
     * @return string
     */
    public function getPrixVenteHT()
    {
        return $this->prixVenteHT;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return ProduitFusion
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
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set devis
     *
     * @param \AppBundle\Entity\Devis $devis
     *
     * @return ProduitFusion
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
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitDevis $produit
     *
     * @return ProduitFusion
     */
    public function addProduit(\AppBundle\Entity\ProduitDevis $produit)
    {
        $this->produits[] = $produit;

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

    public function estVide(){
        return $this->getProduits()->isEmpty();
    }

    public function getSoustotalht()
    {
        return round($this->getQuantite()*$this->getPrixVenteHT(),2);
    }

    public function getPrixRevientUnitaire()
    {
        $prixRevient = 0;

        foreach ($this->produits as $produit) {
            $prixRevient += $produit->getTotalPrixDeRevient();
        } 
        return round($prixRevient,2);
    }

    public function getSommePrixDeVenteHT()
    {
        $SommePrixDeVenteHT = 0;

        foreach ($this->produits as $produit) {
            $SommePrixDeVenteHT += $produit->getSoustotalht();
        } 
        return round($SommePrixDeVenteHT,2);
    }

    public function getPrixRevientTotal()
    {
        return round($this->getPrixRevientUnitaire()*$this->getQuantite(),2);
    }

    public function getMargeBrute(){
        return ($this->getSoustotalht()-$this->getPrixRevientTotal())/$this->getSoustotalht();
    }

    public function getTotalTVA()
    {
        // $totalTVA = 0;

        // foreach ($this->produits as $produit) {$totalTVA += $this->getSoustotalHT()*$produit->getTauxTVA();}
        
        return round($this->getSoustotalHT()*0.2,2);
    }



    /**
     * Set optionnel
     *
     * @param boolean $optionnel
     *
     * @return ProduitFusion
     */
    public function setOptionnel($optionnel)
    {
        $this->optionnel = $optionnel;

        return $this;
    }

    /**
     * Get optionnel
     *
     * @return boolean
     */
    public function getOptionnel()
    {
        return $this->optionnel;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return ProduitFusion
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

}
