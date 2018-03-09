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
     * @var Client
     * @ORM\ManyToOne(targetEntity="OpportunityType")
     */
    private $type;

    /**
     * @var Technology
     * @ORM\ManyToMany(targetEntity="Technology")
     */
    private $technologies;

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
        $this->technologies = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * Add technology
     *
     * @param \AppBundle\Entity\Technology $technology
     *
     * @return Opportunity
     */
    public function addTechnology(\AppBundle\Entity\Technology $technology)
    {
        $this->technologies[] = $technology;

        return $this;
    }

    /**
     * Remove technology
     *
     * @param \AppBundle\Entity\Technology $technology
     */
    public function removeTechnology(\AppBundle\Entity\Technology $technology)
    {
        $this->technologies->removeElement($technology);
    }

    /**
     * Get technologies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTechnologies()
    {
        return $this->technologies;
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
}