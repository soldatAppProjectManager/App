<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LivraisonProduit
 *
 * @ORM\Table(name="livraison_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LivraisonProduitRepository")
 */
class LivraisonProduit
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
     * @var int
     *
     * @ORM\Column(name="quantite", type="smallint")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="Livraison", inversedBy="livraisonProduits" )
     */
    private $livraison;

    /**
     * @ORM\ManyToOne(targetEntity="ProduitBC")
     */
    private $produit;

    /**
     * @ORM\ManyToMany(targetEntity="Serie")
     */
    private $series;

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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return LivraisonProduit
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
     * Set livraison
     *
     * @param \AppBundle\Entity\Livraison $livraison
     *
     * @return LivraisonProduit
     */
    public function setLivraison(\AppBundle\Entity\Livraison $livraison = null)
    {
        $this->livraison = $livraison;

        return $this;
    }

    /**
     * Get livraison
     *
     * @return \AppBundle\Entity\Livraison
     */
    public function getLivraison()
    {
        return $this->livraison;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\ProduitBC $produit
     *
     * @return LivraisonProduit
     */
    public function setProduit(\AppBundle\Entity\ProduitBC $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\ProduitBC
     */
    public function getProduit()
    {
        return $this->produit;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add series
     *
     * @param \AppBundle\Entity\Serie $series
     *
     * @return LivraisonProduit
     */
    public function addSeries(\AppBundle\Entity\Serie $series)
    {
        $this->series[] = $series;

        return $this;
    }

    /**
     * Remove series
     *
     * @param \AppBundle\Entity\Serie $series
     */
    public function removeSeries(\AppBundle\Entity\Serie $series)
    {
        $this->series->removeElement($series);
    }

    /**
     * Get series
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeries()
    {
        return $this->series;
    }
}
