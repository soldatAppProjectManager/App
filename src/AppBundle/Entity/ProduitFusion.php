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
class ProduitFusion extends AbstractProduit
{

    const DISCRIMINATOR = 'fusion';

    /**
    * @ORM\OneToMany(targetEntity="AbstractProduit", mappedBy="ProduitFusion", cascade={"all"})
    * @OrderBy({"ordre" = "ASC"})
    */
    private $produits;

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
     * @param \AppBundle\Entity\AbstractProduit $produit
     *
     * @return ProduitFusion
     */
    public function addProduit(\AppBundle\Entity\AbstractProduit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\AbstractProduit $produit
     */
    public function removeProduit(\AppBundle\Entity\AbstractProduit $produit)
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

    public function getPrixRevientUnitaire()
    {
        $prixRevient = 0;
/** @var ProduitBC $produit */
        foreach ($this->produits as $produit) {
            if($produit->isProduct() && $produit->getDocumentClient()->getId() === $this->getDocumentClient()->getId())
            $prixRevient += $produit->getTotalPrixDeRevient();
        }
        return round($prixRevient,2);
    }

    public function getSommePrixDeVenteHT()
    {
        $SommePrixDeVenteHT = 0;

        /** @var ProduitDevis $produit */
        foreach ($this->produits as $produit) {
            if($produit->getDocumentClient()->getId() === $this->getDocumentClient()->getId()) {
                $SommePrixDeVenteHT += $produit->getSoustotalht();
            }
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
         $totalTVA = 0;

        // foreach ($this->produits as $produit) {$totalTVA += $this->getSoustotalHT()*$produit->getTauxTVA();}

        return round($this->getSoustotalHT() * 0.2,2);
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

    public function getTypeproduit() {
        $type = new TypeProduit();
        return $type->setPrecision(2);
    }
}
