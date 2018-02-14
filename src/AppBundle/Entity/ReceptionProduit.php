<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReceptionProduit
 *
 * @ORM\Table(name="reception_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReceptionProduitRepository")
 */
class ReceptionProduit
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
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="BonDeReception")
     */
    private $bonDeReception;

    /**
     * @ORM\ManyToOne(targetEntity="ProduitBC")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="LieuStock")
     */
    private $lieuStock;

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
     * @return ReceptionProduit
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
     * Set bonDeReception
     *
     * @param \AppBundle\Entity\BonDeReception $bonDeReception
     *
     * @return ReceptionProduit
     */
    public function setBonDeReception(\AppBundle\Entity\BonDeReception $bonDeReception = null)
    {
        $this->bonDeReception = $bonDeReception;

        return $this;
    }

    /**
     * Get bonDeReception
     *
     * @return \AppBundle\Entity\BonDeReception
     */
    public function getBonDeReception()
    {
        return $this->bonDeReception;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\ProduitBC $produit
     *
     * @return ReceptionProduit
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
     * Set lieuStock
     *
     * @param \AppBundle\Entity\LieuStock $lieuStock
     *
     * @return ReceptionProduit
     */
    public function setLieuStock(\AppBundle\Entity\LieuStock $lieuStock = null)
    {
        $this->lieuStock = $lieuStock;

        return $this;
    }

    /**
     * Get lieuStock
     *
     * @return \AppBundle\Entity\LieuStock
     */
    public function getLieuStock()
    {
        return $this->lieuStock;
    }
}
