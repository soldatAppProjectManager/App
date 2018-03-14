<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OProduct
 *
 * @ORM\Table(name="o_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OProductRepository")
 */
class OProduct
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
     * @var Opportunity
     * @ORM\ManyToOne(targetEntity="Opportunity", inversedBy="products")
     */
    private $opportunity;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=128)
     */
    private $designation;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="smallint")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var Technology
     * @ORM\ManyToOne(targetEntity="Technology")
     */
    private $technology;

    /**
     * @var Metier
     *
     * @ORM\ManyToOne(targetEntity="Metier")
     */
    private $trade;

    /**
     * @var TypeProduit
     *
     * @ORM\ManyToOne(targetEntity="TypeProduit")
     */
    private $productType;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return OProduct
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return OProduct
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
     * Set opportunity
     *
     * @param \AppBundle\Entity\Opportunity $opportunity
     *
     * @return OProduct
     */
    public function setOpportunity(\AppBundle\Entity\Opportunity $opportunity = null)
    {
        $this->opportunity = $opportunity;

        return $this;
    }

    /**
     * Get opportunity
     *
     * @return \AppBundle\Entity\Opportunity
     */
    public function getOpportunity()
    {
        return $this->opportunity;
    }

    /**
     * Set trade
     *
     * @param \AppBundle\Entity\Metier $trade
     *
     * @return OProduct
     */
    public function setTrade(\AppBundle\Entity\Metier $trade = null)
    {
        $this->trade = $trade;

        return $this;
    }

    /**
     * Get trade
     *
     * @return \AppBundle\Entity\Metier
     */
    public function getTrade()
    {
        return $this->trade;
    }

    /**
     * Set productType
     *
     * @param \AppBundle\Entity\TypeProduit $productType
     *
     * @return OProduct
     */
    public function setProductType(\AppBundle\Entity\TypeProduit $productType = null)
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * Get productType
     *
     * @return \AppBundle\Entity\TypeProduit
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * Set technology
     *
     * @param \AppBundle\Entity\Technology $technology
     *
     * @return OProduct
     */
    public function setTechnology(\AppBundle\Entity\Technology $technology = null)
    {
        $this->technology = $technology;

        return $this;
    }

    /**
     * Get technology
     *
     * @return \AppBundle\Entity\Technology
     */
    public function getTechnology()
    {
        return $this->technology;
    }
}
