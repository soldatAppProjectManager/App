<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
 */
class Facture
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
     * @ORM\Column(name="reference", type="string", length=16, nullable=true)
     */
    private $reference;

    public function generateRef($count = 0)
    {
        $ref = sprintf(
            'FA%s%s-%s',
            str_repeat('0', AbstractDocumentClient::NBR_ZERO_IN_REFERENCE - strlen((string)$count)),
            $count + 1,
            date('Y'));
        $this->setReference($ref);
    }


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
     * Set numero
     *
     * @param string $numero
     *
     * @return Facture
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

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Facture
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }
}
