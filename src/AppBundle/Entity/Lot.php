<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lot
 *
 * @ORM\Table(name="lot")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LotRepository")
 */
class Lot
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
     *
     * @ORM\Column(name="title", type="string", length=128)
     */
    private $title;


    /**
     * @var Rfp
     * @ORM\ManyToOne(targetEntity="Rfp", inversedBy="lots")
     */
    private $rfp;


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
     * Set title
     *
     * @param string $title
     *
     * @return Lot
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set rfp
     *
     * @param \AppBundle\Entity\Rfp $rfp
     *
     * @return Lot
     */
    public function setRfp(\AppBundle\Entity\Rfp $rfp = null)
    {
        $this->rfp = $rfp;

        return $this;
    }

    /**
     * Get rfp
     *
     * @return \AppBundle\Entity\Rfp
     */
    public function getRfp()
    {
        return $this->rfp;
    }
}
