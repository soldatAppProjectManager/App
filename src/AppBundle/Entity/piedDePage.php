<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * piedDePage
 *
 * @ORM\Table(name="pied_de_page")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\piedDePageRepository")
 */
class piedDePage
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="siteweb", type="string", length=255)
     */
    private $siteweb;

    /**
     * @var string
     *
     * @ORM\Column(name="formejuridique", type="string", length=255)
     */
    private $formejuridique;

    /**
     * @var string
     *
     * @ORM\Column(name="capital", type="string", length=255)
     */
    private $capital;

    /**
     * @var string
     *
     * @ORM\Column(name="rc", type="string", length=255)
     */
    private $rc;

    /**
     * @var string
     *
     * @ORM\Column(name="patente", type="string", length=255)
     */
    private $patente;

    /**
     * @var string
     *
     * @ORM\Column(name="ice", type="string", length=255)
     */
    private $ice;

    /**
     * @var string
     *
     * @ORM\Column(name="cnss", type="string", length=255)
     */
    private $cnss;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return piedDePage
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return piedDePage
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return piedDePage
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return piedDePage
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return piedDePage
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set siteweb
     *
     * @param string $siteweb
     *
     * @return piedDePage
     */
    public function setSiteweb($siteweb)
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    /**
     * Get siteweb
     *
     * @return string
     */
    public function getSiteweb()
    {
        return $this->siteweb;
    }

    /**
     * Set formejuridique
     *
     * @param string $formejuridique
     *
     * @return piedDePage
     */
    public function setFormejuridique($formejuridique)
    {
        $this->formejuridique = $formejuridique;

        return $this;
    }

    /**
     * Get formejuridique
     *
     * @return string
     */
    public function getFormejuridique()
    {
        return $this->formejuridique;
    }

    /**
     * Set capital
     *
     * @param string $capital
     *
     * @return piedDePage
     */
    public function setCapital($capital)
    {
        $this->capital = $capital;

        return $this;
    }

    /**
     * Get capital
     *
     * @return string
     */
    public function getCapital()
    {
        return $this->capital;
    }

    /**
     * Set rc
     *
     * @param string $rc
     *
     * @return piedDePage
     */
    public function setRc($rc)
    {
        $this->rc = $rc;

        return $this;
    }

    /**
     * Get rc
     *
     * @return string
     */
    public function getRc()
    {
        return $this->rc;
    }

    /**
     * Set patente
     *
     * @param string $patente
     *
     * @return piedDePage
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;

        return $this;
    }

    /**
     * Get patente
     *
     * @return string
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * Set ice
     *
     * @param string $ice
     *
     * @return piedDePage
     */
    public function setIce($ice)
    {
        $this->ice = $ice;

        return $this;
    }

    /**
     * Get ice
     *
     * @return string
     */
    public function getIce()
    {
        return $this->ice;
    }

    /**
     * Set cnss
     *
     * @param string $cnss
     *
     * @return piedDePage
     */
    public function setCnss($cnss)
    {
        $this->cnss = $cnss;

        return $this;
    }

    /**
     * Get cnss
     *
     * @return string
     */
    public function getCnss()
    {
        return $this->cnss;
    }
}
