<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractProduit
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="abstract_produit")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap(
 *   {
 *     ProduitDevis::DISCRIMINATOR = ProduitDevis::class,
 *     ProduitFusion::DISCRIMINATOR = ProduitFusion::class,
 *     ProduitBC::DISCRIMINATOR = ProduitBC::class,
 *   }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
abstract class AbstractProduit
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
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=128)
     */
    protected $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 20000
     * )
     * @ORM\Column(name="quantite", type="integer")
     */
    protected $quantite;

    /**
     * @var \Devis
     *
     * @ORM\ManyToOne(targetEntity="AbstractDocumentClient", inversedBy="abstractProduits")
     * @ORM\JoinColumn(name="abstract_document_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $documentClient;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer")
     */
    protected $ordre = 1;

    /**
     * @var bool
     * @Assert\Choice({true, false})
     * @ORM\Column(name="optionnel", type="boolean")
     */
    private $optionnel;

    /**
     * @var string
     * @Assert\Range(
     *      min = 0,
     *      max = 20000000
     * )
     * @ORM\Column(name="prix_vente_ht", type="string", length=255)
     */
    private $prixVenteHT;

    /**
     * @var \ProduitFusion
     *
     * @ORM\ManyToOne(targetEntity="ProduitFusion",inversedBy="produits")
     * @ORM\JoinColumn(name="produit_fusion_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ProduitFusion;

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
     * @return AbstractProduit
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
     * @return AbstractProduit
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
     * Set ordre
     *
     * @param integer $ordre
     *
     * @return AbstractProduit
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDesignation() . " - " . $this->getDescription();
    }


    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return AbstractProduit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
    

    /**
     * Set documentClient
     *
     * @param \AppBundle\Entity\Devis $documentClient
     *
     * @return AbstractProduit
     */
    public function setDocumentClient(AbstractDocumentClient $documentClient = null)
    {
        $this->documentClient = $documentClient;

        return $this;
    }

    /**
     * Get documentClient
     *
     * @return \AppBundle\Entity\Devis
     */
    public function getDocumentClient()
    {
        return $this->documentClient;
    }


    public function getSoustotalht()
    {
        return round($this->getQuantite() * $this->getPrixVenteHT(),2);
    }


    /**
     * Set prixVenteHT
     *
     * @param string $prixVenteHT
     *
     * @return AbstractProduit
     */
    public function setPrixVenteHT($prixVenteHT)
    {
        $this->prixVenteHT = $prixVenteHT;

        return $this;
    }

    /**
     * Get prixVenteHT
     *
     * @return string
     */
    public function getPrixVenteHT()
    {
        return $this->prixVenteHT;
    }


    /**
     * Set optionnel
     *
     * @param boolean $optionnel
     *
     * @return ProduitDevis
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

    public function estFusionne()
    {
        if($this instanceof ProduitDevis or $this instanceof  ProduitBC) {
            return !empty($this->getProduitFusion());
        } else {
            return false;
        }
    }

    public function isProduct() {
        return $this instanceof ProduitDevis or $this instanceof ProduitBC;
    }

    /**
     * Set produitFusion
     *
     * @param \AppBundle\Entity\ProduitFusion $produitFusion
     *
     * @return ProduitDevis
     */
    public function setProduitFusion(\AppBundle\Entity\ProduitFusion $produitFusion = null)
    {
        $this->ProduitFusion = $produitFusion;

        return $this;
    }

    /**
     * Get produitFusion
     *
     * @return \AppBundle\Entity\ProduitFusion
     */
    public function getProduitFusion()
    {
        return $this->ProduitFusion;
    }

    public function getRouteNameEdit()
    {
        if ($this instanceof ProduitFusion)
            return 'ProduitFusion_edit';
        elseif ($this instanceof ProduitBC)
            return 'ProduitBC_edit';
        elseif ($this instanceof ProduitDevis)
            return 'produitdevis_edit';
    }

}
