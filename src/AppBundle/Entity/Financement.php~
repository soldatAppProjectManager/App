<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Financement
 *
 * @ORM\Table(name="financement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FinancementRepository")
 */
class Financement
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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="periode", type="integer")
     */
    private $periode;

    /**
     * @var float
     *
     * @ORM\Column(name="taux", type="float")
     */
    private $taux;

    /**
     * @var string
     *
     * @ORM\Column(name="cout", type="float")
     */
    private $cout;

    /**
     * @var float
     *
     * @ORM\Column(name="loyer", type="float")
     */
    private $loyer;


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
     * @return Financement
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
     * @return Financement
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
     * Set periode
     *
     * @param integer $periode
     *
     * @return Financement
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return int
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set taux
     *
     * @param float $taux
     *
     * @return Financement
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;

        return $this;
    }

    /**
     * Get taux
     *
     * @return float
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set cout
     *
     * @param float $cout
     *
     * @return Financement
     */
    public function setCout($cout)
    {
        $this->cout = $cout;

        return $this;
    }

    /**
     * Get cout
     *
     * @return float
     */
    public function getCout()
    {
        return $this->cout;
    }

    /**
     * Set loyer
     *
     * @param float $loyer
     *
     * @return Financement
     */
    public function setLoyer($loyer)
    {
        $this->loyer = $loyer;

        return $this;
    }

    /**
     * Get loyer
     *
     * @return float
     */
    public function getLoyer()
    {
        return $this->loyer;
    }
}
