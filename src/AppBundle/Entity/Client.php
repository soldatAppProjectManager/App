<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="secteur", type="string", length=255)
     */
    private $secteur;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="address", type="text")
     */
    private $address;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="tel", type="string", length=64)
     */
    private $tel;

    /**
     * @var bool
     * @Assert\Choice({true, false})
     * @ORM\Column(name="prive", type="boolean")
     */
    private $prive;

    /**
     * @var int
     * @Assert\NotBlank()
     * @ORM\Column(name="delaipaiementconstate", type="integer")
     */
    private $delaipaiementconstate;

    /**
     * @var \Monnaie
     * @ORM\ManyToOne(targetEntity="Monnaie")
     * @ORM\JoinColumn(name="Monnaie_id", referencedColumnName="id")
     */
    private $deviseachat;

    /**
    * @ORM\OneToMany(targetEntity="contact", mappedBy="client",cascade={"persist"})
    */
    private $contacts;


    /**
     * @var \commercial
     *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="clients")
     * @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     */
    private $commercial;

    
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

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
     * @return Client
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
     * Set delaipaiementconstate
     *
     * @param integer $delaipaiementconstate
     *
     * @return Client
     */
    public function setDelaipaiementconstate($delaipaiementconstate)
    {
        $this->delaipaiementconstate = $delaipaiementconstate;

        return $this;
    }

    /**
     * Get delaipaiementconstate
     *
     * @return integer
     */
    public function getDelaipaiementconstate()
    {
        return $this->delaipaiementconstate;
    }

    /**
     * Add contact
     *
     * @param \AppBundle\Entity\contact $contact
     *
     * @return Client
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
     * Get contact
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
                // die();

    }

    /**
     * Set secteur
     *
     * @param string $secteur
     *
     * @return Client
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return string
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set prive
     *
     * @param boolean $prive
     *
     * @return Client
     */
    public function setPrive($prive)
    {
        $this->prive = $prive;

        return $this;
    }

    /**
     * Get prive
     *
     * @return boolean
     */
    public function getPrive()
    {
        return $this->prive;
    }

    /**
     * Set commercial
     *
     * @param \AppBundle\Entity\User $commercial
     *
     * @return Client
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

        public function __toString()
    {
        return $this->getNom().", ".$this->getSecteur() ;
    }

    /**
     * Set deviseachat
     *
     * @param \AppBundle\Entity\Monnaie $deviseachat
     *
     * @return Client
     */
    public function setDeviseachat(\AppBundle\Entity\Monnaie $deviseachat = null)
    {
        $this->deviseachat = $deviseachat;

        return $this;
    }

    /**
     * Get deviseachat
     *
     * @return \AppBundle\Entity\Monnaie
     */
    public function getDeviseachat()
    {
        return $this->deviseachat;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Client
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }
}
