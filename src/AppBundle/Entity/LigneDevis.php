<?php

namespace AppBundle\Entity;

use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\ProduitFusion;

class LigneDevis
{

    /**
     * @var int
     */
    private $numero;

    /**
     * @var string
     */
    private $item;

    /**
     * @var string
     */
    private $designation;
    
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $prixunitaire;

    /**
     * @var string
     */
    private $soustotalht;

    /**
     * @var string
     */
    private $quantite;

    private $garanties;

    private $termes;

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return LigneDevis
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set item
     *
     * @param string $item
     *
     * @return LigneDevis
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return LigneDevis
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
     * Set prixunitaire
     *
     * @param string $prixunitaire
     *
     * @return LigneDevis
     */
    public function setPrixunitaire($prixunitaire)
    {
        $this->prixunitaire = $prixunitaire;

        return $this;
    }

    /**
     * Get prixunitaire
     *
     * @return string
     */
    public function getPrixunitaire()
    {
        return $this->prixunitaire;
    }

    /**
     * Set soustotalht
     *
     * @param string $soustotalht
     *
     * @return LigneDevis
     */
    public function setSoustotalht($soustotalht)
    {
        $this->soustotalht = $soustotalht;

        return $this;
    }

    /**
     * Get soustotalht
     *
     * @return string
     */
    public function getSoustotalht()
    {
        return $this->soustotalht;
    }

    /**
     * Set quantite
     *
     * @param string $quantite
     *
     * @return LigneDevis
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }


    public function setProduitDevis(\AppBundle\Entity\ProduitDevis $produitDevis = null)
    {
        $this->ProduitDevis = $produitDevis;

        $this->ProduitFusion = null;        

        $this->setQuantite($this->getProduitDevis()->getQuantite());
        $this->getDevis() == null ? $this->setNumero(1) : $this->setNumero($this->getDevis()->getLignes()->count());
        $this->setItem($this->getNumero());
        $this->setDesignation($this->getProduitDevis()->getDesignation());
        $this->setDescription($this->getProduitDevis()->getDescription());
        $this->setPrixunitaire($this->getProduitDevis()->getPrixVenteHT());
        $this->setSoustotalht($this->getProduitDevis()->getSousTotalHT());

        return $this;
    }




    public function setProduitFusion(\AppBundle\Entity\ProduitFusion $produitFusion = null)
    {
        $this->ProduitFusion = $produitFusion;

        $this->ProduitDevis = null;

        $this->setQuantite($this->getProduitFusion()->getQuantite());
        $this->getDevis() == null ? $this->setNumero(1) : $this->setNumero($this->getDevis()->getLignes()->count());
        $this->setItem($this->getNumero());
        $this->setDesignation($this->getProduitFusion()->getDesignation());
        $this->setDescription($this->getProduitFusion()->getDescription());
        $this->setPrixunitaire($this->getProduitFusion()->getPrixVenteHT());
        $this->setSoustotalht($this->getProduitFusion()->getSousTotalHT());

        return $this;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return LigneDevis
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get Garanties
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }


    /**
     * Set Garanties
     *
     * @param string $designation
     *
     * @return LigneDevis
     */
    public function setGaranties($Garanties)
    {
        $this->garanties = $Garanties;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getGaranties()
    {
        return $this->garanties;
    }


    /**
     * Set Termes
     *
     * @param string $designation
     *
     * @return LigneDevis
     */
    public function setTermes($Termes)
    {
        $this->termes = $Termes;

        return $this;
    }

    /**
     * Get Termes
     *
     * @return string
     */
    public function getTermes()
    {
        return $this->termes;
    }

}
