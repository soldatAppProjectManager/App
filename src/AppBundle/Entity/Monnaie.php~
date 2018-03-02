<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Curl\Curl;
use DateTime;
use \NumberFormatter;

/**
 * monnaie
 *
 * @ORM\Table(name="monnaie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\monnaieRepository")
 */
class Monnaie {
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
     * @ORM\Column(name="symbol", type="string", length=255)
     */
    private $symbol;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var float
     *
     * @ORM\Column(name="taux_achat", type="float")
     */
    private $tauxAchat;

    /**
     * @var float
     *
     * @ORM\Column(name="taux_vente", type="float")
     */
    private $tauxVente;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var float
     *
     * @ORM\Column(name="commission", type="float")
     */
    private $commission;

    /**
     * @var float
     *
     * @ORM\Column(name="volatilite", type="float")
     */
    private $volatilite;


    /**
     * @var int
     *
     * @ORM\Column(name="periode", type="integer")
     */
    private $periode;

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
     * @return monnaie
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
     * Set symbol
     *
     * @param string $symbol
     *
     * @return monnaie
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return monnaie
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set tauxAchat
     *
     * @param float $tauxAchat
     *
     * @return monnaie
     */
    public function setTauxAchat($tauxAchat)
    {
        $this->tauxAchat = $tauxAchat;

        return $this;
    }

    /**
     * Get tauxAchat
     *
     * @return float
     */
    public function getTauxAchat()
    {
        return $this->tauxAchat;
    }

    /**
     * Set tauxVente
     *
     * @param float $tauxVente
     *
     * @return monnaie
     */
    public function setTauxVente($tauxVente)
    {
        $this->tauxVente = $tauxVente;

        return $this;
    }

    /**
     * Get tauxVente
     *
     * @return float
     */
    public function getTauxVente()
    {
        return $this->tauxVente;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return monnaie
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    public function recupererTauxBkam()
    {
        $now = new DateTime("now");

        $commession = 0.01;
        $formateur = new \NumberFormatter( 'fr_FR', NumberFormatter::DECIMAL );

        if ($now->diff($this->getDateCreation())->days > -1) {
            $curl = new Curl();
            $curl->get('http://www.bkam.ma/Marches/Principaux-indicateurs/Marche-des-changes/Cours-de-change/Cours-de-reference');

            $dom = new \DOMDocument('1.0', 'iso-8859-1');
            libxml_use_internal_errors(true);
            $dom->loadHTML($curl->response);
            libxml_use_internal_errors(false);

            $lignes = $dom->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');

            $label = $this->getCode() == "USD" ? "DOLLAR U.S.A" : strtoupper($this->getNom());

            foreach ($lignes as $ligne) {
                if (strpos($ligne->nodeValue ,$label) !== FALSE) {
                    $chaine = trim($ligne->nodeValue);
                    $values = preg_split("/[\s]+/", $chaine);
                    $val = floatval($formateur->parse(preg_replace('/[^0-9.,]+/', '', $values[count($values) - 2])));
                    if($val > 0) {
                        $this->setTauxAchat($val * (1 - $commession));
                        $this->setTauxVente($val * (1 + $commession));
                    }
                }
            }
            $this->setDateCreation($now);
        }
    }

    public function __toString(){
        return $this->getNom().' ('.$this->getCode().') Taux banque vente: '.$this->getTauxVente().'Dh';
    }

    /**
     * Set commission
     *
     * @param float $commission
     *
     * @return Monnaie
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return float
     */
    public function getCommission()
    {
        return $this->commission;
    }



    /**
     * Set volatilite
     *
     * @param float $volatilite
     *
     * @return Monnaie
     */
    public function setVolatilite($volatilite)
    {
        $this->volatilite = $volatilite;

        return $this;
    }

    /**
     * Get volatilite
     *
     * @return float
     */
    public function getVolatilite()
    {
        return $this->volatilite;
    }




    /**
     * Set periode
     *
     * @param integer $periode
     *
     * @return Monnaie
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

        return $this;
    }

    /**
     * Get periode
     *
     * @return integer
     */
    public function getPeriode()
    {
        return $this->periode;
    }
}
