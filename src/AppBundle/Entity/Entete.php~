<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Entete
 *
 * @ORM\Table(name="entete")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnteteRepository")
 */
class Entete
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="texte", type="text")
     */
    private $texte;


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="langue", type="text")
     */
    private $langue;


    /**
     * @var bool
     * @Assert\Choice({true, false})
     * @ORM\Column(name="genre", type="boolean")
     */
    private $genre;  


    public function __toString()
    {
        if ($this->getGenre()) return $this->getTitre()." - ".$this->getLangue()." - Feminin";
        else return $this->getTitre()." - ".$this->getLangue()." - Masculin";
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Entete
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
     * Set texte
     *
     * @param string $texte
     *
     * @return Entete
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set langue
     *
     * @param string $langue
     *
     * @return Entete
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
     * Set genre
     *
     * @param string $genre
     *
     * @return Entete
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }
}
