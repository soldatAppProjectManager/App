<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaiementRepository")
 */
class Paiement
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
     * @var ModePaiement
     * @ORM\ManyToOne(targetEntity="ModePaiement")
     * @ORM\JoinColumn(name="mode_paiement_id", referencedColumnName="id")
     */
    private $mode;

    /**
     * @var Facture
     * @ORM\ManyToOne(targetEntity="Facture")
     * @ORM\JoinColumn(name="facture_id", referencedColumnName="id")
     */
    private $facture;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="decimal", precision=10, scale=2)
     */
    private $valeur;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=64)
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity="FileSd", cascade={"persist"})
     */
    private $file;


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
     * @return Paiement
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
     * Set valeur
     *
     * @param string $valeur
     *
     * @return Paiement
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Paiement
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set mode
     *
     * @param \AppBundle\Entity\ModePaiement $mode
     *
     * @return Paiement
     */
    public function setMode(\AppBundle\Entity\ModePaiement $mode = null)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get mode
     *
     * @return \AppBundle\Entity\ModePaiement
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return Paiement
     */
    public function setFacture(\AppBundle\Entity\Facture $facture = null)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \AppBundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Paiement
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Paiement
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set file
     *
     * @param \AppBundle\Entity\FileSd $file
     *
     * @return Paiement
     */
    public function setFile(\AppBundle\Entity\FileSd $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \AppBundle\Entity\FileSd
     */
    public function getFile()
    {
        return $this->file;
    }
}
