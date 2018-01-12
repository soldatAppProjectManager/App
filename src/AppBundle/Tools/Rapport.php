<?php

namespace AppBundle\Tools;

use AppBundle\Entity\Metier;
use AppBundle\Entity\TypeProduit;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\Devis;
use AppBundle\Entity\statutProduit;
use AppBundle\Entity\User;
use AppBundle\Entity\Fournisseur;

use Symfony\Component\Validator\Constraints as Assert;


use DateTime;

class Rapport
{

    /**
     * @var \User
     *
     */
    private $commercial;    

    /**
     * @var DateTime
     * @Assert\NotBlank()
     */
    private $debut;

    /**
     * @var DateTime
     * @Assert\NotBlank()
     */
    private $fin;

    /**
     * @var \Metier
     *
     */
    private $metier;

    /**
     * @var \TypeProduit
     *
     */
    private $typeproduit;

    /**
     * @var \Client
     *
     */
    private $client;

    /**
     * @var \statutProduit
     *
     */
    private $statut;


    /**
     * @var \Fournisseur
     *
     */
    private $fournisseur;


    public function setCommercial(AppBundle\Entity\User $commercial){
        $this->commercial=$commercial;
    }

    public function setDebut($debut){
        $this->debut=$debut;
    }

    public function setFin($fin){
        $this->fin=$fin;
    }

    public function setMetier(AppBundle\Entity\Metier $metier){
        $this->metier=$metier;
    }

    public function setTypeproduit(AppBundle\Entity\typeproduit $typeproduit){
        $this->typeproduit=$typeproduit;
    }

    public function setClient(AppBundle\Entity\Client $client){
        $this->client=$client;
    }

    public function setStatut(AppBundle\Entity\statutProduit $statut){
        $this->statut=$statut;
    }

    public function setFournisseur(AppBundle\Entity\Fournisseur $fournisseur){
        $this->fournisseur=$fournisseur;
    }

    public function getCommercial(){
        return $this->commercial;
    }

    public function getDebut(){
        return $this->debut;
    }

    public function getFin(){
        return $this->fin;
    }

    public function getMetier(){
        return $this->metier;
    }

    public function getTypeproduit(){
        return $this->typeproduit;
    }

    public function getClient(){
        return $this->client;
    }

    public function getStatut(){
        return $this->statut;
    }

    public function getFournisseur(){
        return $this->fournisseur;
    }

}
