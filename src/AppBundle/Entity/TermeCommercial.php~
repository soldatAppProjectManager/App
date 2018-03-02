<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\ProduitDevis;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TermeCommercial
 *
 * @ORM\Table(name="terme_commercial")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TermeCommercialRepository")
 */
class TermeCommercial
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
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * @var \destinataire
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="CategorieTerme")
     * @ORM\JoinColumn(name="CategorieTerme_id", referencedColumnName="id")
     */
    private $categorie;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="ProduitDevis", mappedBy="termes")
     */
    private $produits;    


    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="Devis", mappedBy="termes")
     */
    private $devis;

    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="BonDeCommandeClient", mappedBy="termes")
     */
    private $bonDeCommandes;    

    public function __toString()
    {
        return $this->getNom()."-".substr($this->getDescription(),0,50)."...";
    }

    public function __construct()
    {
        $this->devis = new ArrayCollection();
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->devis = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TermeCommercial
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
     * @return TermeCommercial
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
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitDevis $produit
     *
     * @return TermeCommercial
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

    /**
     * Add devi
     *
     * @param \AppBundle\Entity\Devis $devi
     *
     * @return TermeCommercial
     */
    public function addDevi(\AppBundle\Entity\Devis $devi)
    {
        $this->devis[] = $devi;

        return $this;
    }

    /**
     * Remove devi
     *
     * @param \AppBundle\Entity\Devis $devi
     */
    public function removeDevi(\AppBundle\Entity\Devis $devi)
    {
        $this->devis->removeElement($devi);
    }

    /**
     * Get devis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDevis()
    {
        return $this->devis;
    }

    /**
     * Add bonDeCommande
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommande
     *
     * @return TermeCommercial
     */
    public function addBonDeCommande(\AppBundle\Entity\BonDeCommandeClient $bonDeCommande)
    {
        $this->bonDeCommandes[] = $bonDeCommande;

        return $this;
    }

    /**
     * Remove bonDeCommande
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommande
     */
    public function removeBonDeCommande(\AppBundle\Entity\BonDeCommandeClient $bonDeCommande)
    {
        $this->bonDeCommandes->removeElement($bonDeCommande);
    }

    /**
     * Get bonDeCommandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBonDeCommandes()
    {
        return $this->bonDeCommandes;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return TermeCommercial
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}
