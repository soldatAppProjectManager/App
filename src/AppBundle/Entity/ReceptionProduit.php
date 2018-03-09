<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var int
     */
    public $numSeries;

    /**
     * @ORM\ManyToOne(targetEntity="BonDeReception", inversedBy="receptionProduits" )
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
     * @ORM\OneToMany(targetEntity="Serie", mappedBy="receptionProduit", cascade={"persist","remove"})
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
     * @return ReceptionProduit
     */
    public function addSeries(\AppBundle\Entity\Serie $series)
    {
        if (!$this->series->contains($series)) {
            $series->setReceptionProduit($this);
            $this->series[] = $series;
        }

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

    public function setSeries()
    {
        return $this->series = new ArrayCollection();
    }


    public function implodeSerie()
    {
        /** @var Serie $serie */
        foreach ($this->getSeries() as $serie) {
            $this->numSeries = $this->numSeries . ',' . $serie->getNumero();
        }
    }

    public function explodeSerie()
    {
        $nSeries = explode(",", $this->numSeries);
        foreach ($nSeries as $ns) {
            $serie = new Serie();
            $serie->setNumero($ns);
            $this->addSeries($serie);
        }
    }
}
