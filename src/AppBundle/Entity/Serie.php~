<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Serie
 *
 * @ORM\Table(name="serie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SerieRepository")
 */
class Serie
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
     * @ORM\Column(name="numero", type="string", length=128)
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity="ReceptionProduit", inversedBy="series" )
     */
    private $receptionProduit;

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
     * Set numero
     *
     * @param string $numero
     *
     * @return Serie
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
     * Set receptionProduit
     *
     * @param \AppBundle\Entity\ReceptionProduit $receptionProduit
     *
     * @return Serie
     */
    public function setReceptionProduit(\AppBundle\Entity\ReceptionProduit $receptionProduit = null)
    {
        $this->receptionProduit = $receptionProduit;

        return $this;
    }

    /**
     * Get receptionProduit
     *
     * @return \AppBundle\Entity\ReceptionProduit
     */
    public function getReceptionProduit()
    {
        return $this->receptionProduit;
    }

    public function __toString()
    {
        return $this->getNumero();
    }

}
