<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Traits\CreatedByTrait;
use AppBundle\Entity\Traits\GenerateRefTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
 */
class Facture
{
    use CreatedByTrait, TimestampableEntity, GenerateRefTrait;

    const REF_FORMAT = 'FA%s%s-%s';

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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;

    /**
     * @var BonDeCommandeClient
     * @ORM\ManyToOne(targetEntity="BonDeCommandeClient")
     * @ORM\JoinColumn(name="bcc_id", referencedColumnName="id")
     */
    private $bonDeCommandeClient;

    /**
     * @var ModePaiement
     * @ORM\ManyToOne(targetEntity="ModePaiement")
     * @ORM\JoinColumn(name="mode_paiement_id", referencedColumnName="id")
     */
    private $modePaiement;

    /**
     * @ORM\OneToMany(targetEntity="Paiement", mappedBy="facture")
     */
    private $paiements;

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
     * @return Facture
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Facture
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set bonDeCommandeClient
     *
     * @param \AppBundle\Entity\BonDeCommandeClient $bonDeCommandeClient
     *
     * @return Facture
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->paiements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     *
     * @return Facture
     */
    public function addPaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements[] = $paiement;

        return $this;
    }

    /**
     * Remove paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     */
    public function removePaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements->removeElement($paiement);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }

    public function getTotalPaiements()
    {
        $total = 0;
        /** @var Paiement $paiement */
        foreach ($this->getPaiements() as $paiement) {
            $total += $paiement->getValeur();
        }

        return $total;
    }

    public function getRestePayer()
    {
        return $this->getBonDeCommandeClient()->getTotalTTC() - $this->getTotalPaiements();
    }

    /**
     * Set modePaiement
     *
     * @param \AppBundle\Entity\ModePaiement $modePaiement
     *
     * @return Facture
     */
    public function setModePaiement(\AppBundle\Entity\ModePaiement $modePaiement = null)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return \AppBundle\Entity\ModePaiement
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }
}
