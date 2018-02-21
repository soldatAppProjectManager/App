<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LivraisonRepository")
 */
class Livraison
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="livreur", type="string", length=128)
     */
    private $livreur;

    /**
     * @var Fournisseur
     * @ORM\ManyToOne(targetEntity="BonDeCommandeClient")
     * @ORM\JoinColumn(name="bcc_id", referencedColumnName="id")
     */
    private $bonDeCommandeClient;

    /**
     * @ORM\OneToMany(targetEntity="LivraisonProduit", mappedBy="livraison", cascade={"persist"})
     */
    private $livraisonProduits;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Livraison
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Livraison
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set livreur
     *
     * @param string $livreur
     *
     * @return Livraison
     */
    public function setLivreur($livreur)
    {
        $this->livreur = $livreur;

        return $this;
    }

    /**
     * Get livreur
     *
     * @return string
     */
    public function getLivreur()
    {
        return $this->livreur;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->livraisonProduits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add livraisonProduit
     *
     * @param \AppBundle\Entity\LivraisonProduit $livraisonProduit
     *
     * @return Livraison
     */
    public function addLivraisonProduit(\AppBundle\Entity\LivraisonProduit $livraisonProduit)
    {
        $livraisonProduit->setLivraison($this);
        $this->livraisonProduits[] = $livraisonProduit;

        return $this;
    }

    /**
     * Remove livraisonProduit
     *
     * @param \AppBundle\Entity\LivraisonProduit $livraisonProduit
     */
    public function removeLivraisonProduit(\AppBundle\Entity\LivraisonProduit $livraisonProduit)
    {
        $this->livraisonProduits->removeElement($livraisonProduit);
    }

    /**
     * Get livraisonProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLivraisonProduits()
    {
        return $this->livraisonProduits;
    }

    /**
     * Set bonDeCommandeClient
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommandeClient
     *
     * @return Livraison
     */
    public function setBonDeCommandeClient(\AppBundle\Entity\BonDeCommandeClient $bonDeCommandeClient = null)
    {
        $this->bonDeCommandeClient = $bonDeCommandeClient;

        return $this;
    }

    /**
     * Get bonDeCommandeClient
     *
     * @return \AppBundle\Entity\BonDeCommandeClient
     */
    public function getBonDeCommandeClient()
    {
        return $this->bonDeCommandeClient;
    }
}
