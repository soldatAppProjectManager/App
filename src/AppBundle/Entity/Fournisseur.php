<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\contact;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Fournisseur
 *
 * @ORM\Table(name="fournisseur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FournisseurRepository")
 */
class Fournisseur
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \Monnaie
     *
     * @ORM\ManyToOne(targetEntity="Monnaie")
     * @ORM\JoinColumn(name="Monnaie_id", referencedColumnName="id")
     */
    private $devisevente;

    /**
     * @var float
     *
     * @ORM\Column(name="fa", type="float")
     */
    private $fa;

    /**
     * @var float
     *
     * @ORM\Column(name="faSurtaxe", type="float")
     */
    private $faSurtaxe;

    /**
     * @var int
     *
     * @ORM\Column(name="termepaiement", type="integer")
     */
    private $termepaiement;

    /**
    * @ORM\OneToMany(targetEntity="contact", mappedBy="fournisseur",cascade={"persist"})
    */
    private $contacts;
    
    /**
     * @var ModeleBCF
     * @ORM\ManyToOne(targetEntity="ModeleBCF")
     * @ORM\JoinColumn(name="modele_bcf_id", referencedColumnName="id")
     */
    private $modele;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Fournisseur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set fa
     *
     * @param float $fa
     *
     * @return Fournisseur
     */
    public function setFa($fa)
    {
        $this->fa = $fa;

        return $this;
    }

    /**
     * Get fa
     *
     * @return float
     */
    public function getFa()
    {
        return $this->fa;
    }

    /**
     * Set termepaiement
     *
     * @param integer $termepaiement
     *
     * @return Fournisseur
     */
    public function setTermepaiement($termepaiement)
    {
        $this->termepaiement = $termepaiement;

        return $this;
    }

    /**
     * Get termepaiement
     *
     * @return int
     */
    public function getTermepaiement()
    {
        return $this->termepaiement;
    }

    /**
     * Set devisevente
     *
     * @param \AppBundle\Entity\Monnaie $devisevente
     *
     * @return Fournisseur
     */
    public function setDevisevente(\AppBundle\Entity\Monnaie $devisevente = null)
    {
        $this->devisevente = $devisevente;

        return $this;
    }

    /**
     * Get devisevente
     *
     * @return \AppBundle\Entity\Monnaie
     */
    public function getDevisevente()
    {
        return $this->devisevente;
    }


    public function __toString(){
        return $this->getNom().' ('.$this->getDevisevente()->getCode().') '.$this->getTermepaiement().'J';
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contact
     *
     * @param \AppBundle\Entity\contact $contact
     *
     * @return Fournisseur
     */
    public function addContact(\AppBundle\Entity\contact $contact)
    {
       if ($this->getContacts()->count()==0) {
                $contact->setDefaut(true);             
            }
            
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \AppBundle\Entity\contact $contact
     */
    public function removeContact(\AppBundle\Entity\contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }


        public function setContactParDefaut(\AppBundle\Entity\contact $contactDefaut)
    {
        foreach ($this->contacts as $contact) {
            $contact === $contactDefaut ? $contact->setDefaut(true) : $contact->setDefaut(false);
        }

    }

        public function getContactParDefaut()
    {
        foreach ($this->contacts as $contact) {
            if ($contact->getDefaut()) {return $contact;} 
                }

    }



    /**
     * Set faSurtaxe
     *
     * @param float $faSurtaxe
     *
     * @return Fournisseur
     */
    public function setFaSurtaxe($faSurtaxe)
    {
        $this->faSurtaxe = $faSurtaxe;

        return $this;
    }

    /**
     * Get faSurtaxe
     *
     * @return float
     */
    public function getFaSurtaxe()
    {
        return $this->faSurtaxe;
    }

    /**
     * Set modele
     *
     * @param \AppBundle\Entity\ModeleBCF $modele
     *
     * @return Fournisseur
     */
    public function setModele(\AppBundle\Entity\ModeleBCF $modele = null)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return \AppBundle\Entity\ModeleBCF
     */
    public function getModele()
    {
        return $this->modele;
    }
}
