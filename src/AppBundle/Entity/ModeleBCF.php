<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * modeleDevis
 *
 * @ORM\Table(name="modele_bcf")
 * @ORM\Entity()
 */
class ModeleBCF
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="modele", type="string", length=255)
     */
    private $modele;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="langue", type="string", length=255)
     */
    private $langue;

    /**
     * @var string
     * @Assert\Choice({"portrait", "landscape"})
     * @ORM\Column(name="miseenpage", type="string", length=255)
     */
    private $miseenpage;


    /**
     * @var \piedDePage
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="piedDePage")
     * @ORM\JoinColumn(name="piedDePage_id", referencedColumnName="id")
     */
    private $piedDePage;


    public function __toString(){
        return $this->getNom()."-".$this->getLangue()."-".substr($this->getDescription(),0,50)."...";
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
     * @return modeleDevis
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
     * Set description
     *
     * @param string $description
     *
     * @return modeleDevis
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
     * Set modele
     *
     * @param string $modele
     *
     * @return modeleDevis
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set langue
     *
     * @param string $langue
     *
     * @return modeleDevis
     */
    public function setLangue($langue)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return string
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set miseenpage
     *
     * @param string $miseenpage
     *
     * @return modeleDevis
     */
    public function setMiseenpage($miseenpage)
    {
        $this->miseenpage = $miseenpage;

        return $this;
    }

    /**
     * Get miseenpage
     *
     * @return string
     */
    public function getMiseenpage()
    {
        return $this->miseenpage;
    }

    /**
     * Set piedDePage
     *
     * @param \AppBundle\Entity\piedDePage $piedDePage
     *
     * @return ModeleBCF
     */
    public function setPiedDePage(\AppBundle\Entity\piedDePage $piedDePage = null)
    {
        $this->piedDePage = $piedDePage;

        return $this;
    }

    /**
     * Get piedDePage
     *
     * @return \AppBundle\Entity\piedDePage
     */
    public function getPiedDePage()
    {
        return $this->piedDePage;
    }
}
