<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\ProduitDevis;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * garantie
 *
 * @ORM\Table(name="garantie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\garantieRepository")
 */
class garantie
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer")
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="disponibilite", type="string", length=255)
     */
    private $disponibilite;

    /**
     * @var int
     *
     * @ORM\Column(name="intervention", type="integer")
     */
    private $intervention;

    /**
     * @var int
     *
     * @ORM\Column(name="reparation", type="integer")
     */
    private $reparation;

    /**
     * @var string
     *
     * @ORM\Column(name="supplement", type="string", length=255)
     */
    private $supplement;

    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="ProduitDevis", mappedBy="garanties")
     */
    private $produits;


    public function __toString()
    {
        return "Garantie ".$this->getNom()." - ".$this->getDuree()."Mois - Intervention sous ".$this->getIntervention()."H" ;
    }

    public function __construct()
    {
    $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return garantie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return garantie
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
     * Set duree
     *
     * @param integer $duree
     *
     * @return garantie
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set disponibilite
     *
     * @param string $disponibilite
     *
     * @return garantie
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * Get disponibilite
     *
     * @return string
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Set intervention
     *
     * @param integer $intervention
     *
     * @return garantie
     */
    public function setIntervention($intervention)
    {
        $this->intervention = $intervention;

        return $this;
    }

    /**
     * Get intervention
     *
     * @return int
     */
    public function getIntervention()
    {
        return $this->intervention;
    }

    /**
     * Set reparation
     *
     * @param integer $reparation
     *
     * @return garantie
     */
    public function setReparation($reparation)
    {
        $this->reparation = $reparation;

        return $this;
    }

    /**
     * Get reparation
     *
     * @return int
     */
    public function getReparation()
    {
        return $this->reparation;
    }

    /**
     * Set supplement
     *
     * @param string $supplement
     *
     * @return garantie
     */
    public function setSupplement($supplement)
    {
        $this->supplement = $supplement;

        return $this;
    }

    /**
     * Get supplement
     *
     * @return string
     */
    public function getSupplement()
    {
        return $this->supplement;
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitDevis $produit
     *
     * @return garantie
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
}
