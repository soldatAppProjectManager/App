<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\UpdatedByTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Opportunity
 *
 * @ORM\Table(name="opportunity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OpportunityRepository")
 */
class Opportunity
{
    use TimestampableEntity, CreatedByTrait, UpdatedByTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $salesEngineer;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $preSale;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client")
     */
    private $customer;

    /**
     * @var contact
     * @ORM\ManyToOne(targetEntity="contact")
     */
    private $contact;

    /**
     * @var OpportunityType
     * @ORM\ManyToOne(targetEntity="OpportunityType")
     */
    private $type;

    /**
     * @var OpportunityStatus
     * @ORM\ManyToOne(targetEntity="OpportunityStatus")
     */
    private $status;

    /**
     * @var AcquisitionMode
     * @ORM\ManyToOne(targetEntity="AcquisitionMode")
     */
    private $acquisitionMode;

    /**
     * @var OpportunityProbability
     * @ORM\ManyToOne(targetEntity="OpportunityProbability")
     */
    private $probability;

    /**
     * @ORM\OneToMany(targetEntity="OProduct", mappedBy="opportunity", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $products;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text")
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $dateEcheance;

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
     * Set label
     *
     * @param string $label
     *
     * @return Opportunity
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set salesEngineer
     *
     * @param \AppBundle\Entity\User $salesEngineer
     *
     * @return Opportunity
     */
    public function setSalesEngineer(\AppBundle\Entity\User $salesEngineer = null)
    {
        $this->salesEngineer = $salesEngineer;

        return $this;
    }

    /**
     * Get salesEngineer
     *
     * @return \AppBundle\Entity\User
     */
    public function getSalesEngineer()
    {
        return $this->salesEngineer;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Client $customer
     *
     * @return Opportunity
     */
    public function setCustomer(\AppBundle\Entity\Client $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Client
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\OpportunityType $type
     *
     * @return Opportunity
     */
    public function setType(\AppBundle\Entity\OpportunityType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\OpportunityType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Opportunity
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set preSale
     *
     * @param \AppBundle\Entity\User $preSale
     *
     * @return Opportunity
     */
    public function setPreSale(\AppBundle\Entity\User $preSale = null)
    {
        $this->preSale = $preSale;

        return $this;
    }

    /**
     * Get preSale
     *
     * @return \AppBundle\Entity\User
     */
    public function getPreSale()
    {
        return $this->preSale;
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\OProduct $product
     *
     * @return Opportunity
     */
    public function addProduct(\AppBundle\Entity\OProduct $product)
    {
        if (!$this->products->contains($product)) {
            $product->setOpportunity($this);
            $this->products[] = $product;
        }

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\OProduct $product
     */
    public function removeProduct(\AppBundle\Entity\OProduct $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function getTotal()
    {
        $total = 0;
        /** @var OProduct $product */
        foreach ($this->getProducts() as $product) {
            $total += $product->getQuantity() * $product->getPrice();
        }

        return $total;
    }

    /**
     * Set contact
     *
     * @param \AppBundle\Entity\contact $contact
     *
     * @return Opportunity
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
     * Set acquisitionMode
     *
     * @param \AppBundle\Entity\AcquisitionMode $acquisitionMode
     *
     * @return Opportunity
     */
    public function setAcquisitionMode(\AppBundle\Entity\AcquisitionMode $acquisitionMode = null)
    {
        $this->acquisitionMode = $acquisitionMode;

        return $this;
    }

    /**
     * Get acquisitionMode
     *
     * @return \AppBundle\Entity\AcquisitionMode
     */
    public function getAcquisitionMode()
    {
        return $this->acquisitionMode;
    }

    /**
     * Set probability
     *
     * @param \AppBundle\Entity\OpportunityProbability $probability
     *
     * @return Opportunity
     */
    public function setProbability(\AppBundle\Entity\OpportunityProbability $probability = null)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability
     *
     * @return \AppBundle\Entity\OpportunityProbability
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set status
     *
     * @param \AppBundle\Entity\OpportunityStatus $status
     *
     * @return Opportunity
     */
    public function setStatus(\AppBundle\Entity\OpportunityStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \AppBundle\Entity\OpportunityStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateEcheance.
     *
     * @param \DateTime $dateEcheance
     *
     * @return Opportunity
     */
    public function setDateEcheance($dateEcheance)
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    /**
     * Get dateEcheance.
     *
     * @return \DateTime
     */
    public function getDateEcheance()
    {
        return $this->dateEcheance;
    }
}
