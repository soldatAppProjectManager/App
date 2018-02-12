<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AbstractDocumentClient
 *
 *
 * @ORM\InheritanceType("JOINED")
 * @ORM\Table(name="abstract_document_client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AbstractDocumentClientRepository")
 */
abstract class AbstractDocumentClient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="commercial_id", referencedColumnName="id")
     */
    protected $commercial;

    /**
     * @var \Client
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;

    /**
     * @var \Client
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    protected $contact;

    /**
     * @ORM\OneToMany(targetEntity="AbstractProduit", mappedBy="documentClient", cascade={"persist"})
     * @ORM\OrderBy({"ordre" = "ASC"})
     */
    protected $abstractProduits;

    /**
     * Many BC have Many termes.
     * @ORM\ManyToMany(targetEntity="TermeCommercial", inversedBy="bonDeCommandes")
     * @ORM\JoinTable(name="document_client_terme")
     */
    protected $termes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="datetime")
     */
    protected $datecreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datemodification", type="datetime")
     */
    protected $datemodification;

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
     * Constructor
     */
    public function __construct()
    {
        $this->abstractProduits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->termes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setDatecreation(new \DateTime());
        $this->setDatemodification(new \DateTime());
    }

    /**
     * Set datecreation
     *
     * @param \DateTime $datecreation
     *
     * @return AbstractDocumentClient
     */
    public function setDatecreation($datecreation)
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * Get datecreation
     *
     * @return \DateTime
     */
    public function getDatecreation()
    {
        return $this->datecreation;
    }

    /**
     * Set datemodification
     *
     * @param \DateTime $datemodification
     *
     * @return AbstractDocumentClient
     */
    public function setDatemodification($datemodification)
    {
        $this->datemodification = $datemodification;

        return $this;
    }

    /**
     * Get datemodification
     *
     * @return \DateTime
     */
    public function getDatemodification()
    {
        return $this->datemodification;
    }

    /**
     * Set commercial
     *
     * @param \AppBundle\Entity\User $commercial
     *
     * @return AbstractDocumentClient
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
     * @return AbstractDocumentClient
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
     * Add abstractProduit
     *
     * @param \AppBundle\Entity\AbstractProduit $abstractProduit
     *
     * @return AbstractDocumentClient
     */
    public function addAbstractProduit(AbstractProduit $abstractProduit)
    {
        $abstractProduit->setDocumentClient($this);
        if(null == $abstractProduit->getOrdre()) {
            $abstractProduit->setOrdre($this->getAbstractProduits()->count() + 1);
        }

        $this->abstractProduits->add($abstractProduit);

        return $this;
    }

    /**
     * Remove abstractProduit
     *
     * @param \AppBundle\Entity\AbstractProduit $abstractProduit
     */
    public function removeAbstractProduit(\AppBundle\Entity\AbstractProduit $abstractProduit)
    {
        $this->abstractProduits->removeElement($abstractProduit);
    }

    /**
     * Get abstractProduits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAbstractProduits()
    {
        return $this->abstractProduits;
    }

    /**
     * Add terme
     *
     * @param \AppBundle\Entity\TermeCommercial $terme
     *
     * @return AbstractDocumentClient
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


    /**
     * Set contact
     *
     * @param \AppBundle\Entity\contact $contact
     *
     * @return BonDeCommandeClient
     */
    public function setContact(\AppBundle\Entity\contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \AppBundle\Entity\contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProduits()
    {
        return $this->abstractProduits->filter(function(AbstractProduit $abstractProduit) {
            if($abstractProduit instanceof ProduitDevis or $abstractProduit instanceof ProduitBC) {
                return $abstractProduit;
            }
        });
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProduitsFusion()
    {
        return $this->abstractProduits->filter(function(AbstractProduit $abstractProduit) {
            if($abstractProduit instanceof ProduitFusion) {
                return $abstractProduit;
            }
        });
    }


    public function getTotalHT()
    {
        $totalHT = 0;
        /** @var ProduitDevis $produit */
        foreach ($this->getAbstractProduits() as $produit) {
            if (!$produit->getOptionnel() && !$produit->estFusionne()){
                $totalHT += $produit->getSoustotalht();
            }
        }

        return round($totalHT, 2);
    }

}
