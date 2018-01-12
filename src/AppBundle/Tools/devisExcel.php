<?php

namespace AppBundle\Tools;

use DateTime;

use \PHPExcel;
use \PHPExcel_IOFactory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\Devis;

class devisExcel 
{
    private $premierindexproduit;
    private $dernierindexproduit;
    private $client;
    private $commercial;
    private $produits;
    private $TravailLivraison;
    private $TravailAvantVente;
    private $TravailCommercial;
    private $TravailImport;
    private $Travailminimum;
    private $objPHPExcel;
    private $file;
    private $devis;
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em,$file,$repertoireImport,$devis){
        
        $this->objPHPExcel = $objPHPExcel;
        $this->em=$em;
        $this->produits = [];
        $this->devis = $devis;        

        $fileName=md5(uniqid().'.'.$file->guessExtension());

        $file->move($repertoireImport,$fileName);

        $this->objPHPExcel = PHPExcel_IOFactory::createReaderForFile($repertoireImport."/".$fileName)->load($repertoireImport."/".$fileName);

       /*
        * COLLECTER LES INDEX : On prend comme repere la plage 'PLAGE_CA' pour attribuer les valeurs aux variables de classes d'index des produits
        */
        $plage_repere = $this->objPHPExcel->getNamedRange('PLAGE_CA');

        $plage_repere_premiere_celule=substr($plage_repere->getRange(),0,strpos($plage_repere->getRange(),':'));
        $plage_repere_derniere_celule=substr($plage_repere->getRange(),strpos($plage_repere->getRange(),':')+1,strlen($plage_repere->getRange())-strpos($plage_repere->getRange(),':')-1);

        $this->premierindexproduit = $this->objPHPExcel->getSheet(0)->getCell($plage_repere_premiere_celule)->getRow();
        $this->dernierindexproduit = $this->objPHPExcel->getSheet(0)->getCell($plage_repere_derniere_celule)->getRow();
        /*################################################################################################*/

        /*
        * COLLECTER LES PLAGE A CELLULE UNIQUE : attribuer les valeurs aux variables de classes
        */

        $rangeClient = $this->objPHPExcel->getNamedRange('CLIENT')->getRange();
        $rangeCommercial= $this->objPHPExcel->getNamedRange('IC')->getRange();

        $this->client = $this->objPHPExcel->getSheet(0)->getCell($rangeClient)->getValue();
        $this->commercial = $this->objPHPExcel->getSheet(0)->getCell($rangeCommercial)->getValue();
        $this->TravailLivraison =  devisExcel::getTravailLivraisonId($this->objPHPExcel->getSheet(2)->getCell('D6')->getValue());
        $this->TravailAvantVente = devisExcel::getTravailAvantVenteId($this->objPHPExcel->getSheet(2)->getCell('A6')->getValue());
        $this->TravailCommercial = devisExcel::getTravailCommercialId($this->objPHPExcel->getSheet(2)->getCell('B6')->getValue());
        $this->TravailImport = devisExcel::getTravailImportId($this->objPHPExcel->getSheet(2)->getCell('C6')->getValue());
        $this->Travailminimum = $this->objPHPExcel->getSheet(5)->getCell('B16')->getValue();
        /*################################################################################################*/

        for ($i = $this->premierindexproduit; $i <= $this->dernierindexproduit; $i++) {
            if ($this->isProduit($i)){$this->getProduit($i);}
        }

        $this->buildDevis();
        $this->buildProduits();

        $fs = new Filesystem();
        $fs->remove($tmpfname);

    }

    public function buildDevis(){

        $this->devis->setTravailLivraison($this->em->getRepository('AppBundle:TravailLivraison')->find($this->getTravailLivraison()));
        $this->devis->setTravailAvantVente($this->em->getRepository('AppBundle:TravailAvantVente')->find($this->getTravailAvantVente()));
        $devis->setTravailCommercial($this->em->getRepository('AppBundle:TravailCommercial')->find($this->getTravailCommercial()));
        $devis->setTravailImport($this->em->getRepository('AppBundle:TravailImport')->find($this->getTravailImport()));
        return $this->devis;     
    }

    public function buildProduits(){
        
        $i=1;
        foreach($this->getProduits() as $produitExcel){

            $produit = new produitdevis;

            $produit->setDevis($produit->devis);
            $this->devis->addProduit($produit);

            $fournisseur =  $this->em->getRepository('AppBundle:Fournisseur')->find($produitExcel['fournisseur']);

            $produit->setReference(" ");
            $produit->setNumero($i++);
            $produit->setTauxSpecial(false);

            $produit->setQuantite($produitExcel['quantite']);
            $produit->setDescription($produitExcel['description']);
            $produit->setDesignation(" ");
            $produit->setMarge($produitExcel['marge']);
            $produit->setReferenceoffre("Inconnue");
            $produit->setPrixachatht($produitExcel['prixachatht']);
            $produit->setFraisapproche($produitExcel['fraisapproche']);
            $produit->setTermepaiementfournisseur($produitExcel['quantite']);
            $produit->setMetier($this->em->getRepository('AppBundle:Metier')->find($produitExcel['metier']));
            $produit->setTypeproduit($this->em->getRepository('AppBundle:TypeProduit')->find($produitExcel['typeproduit']));
            $produit->setDeviseachat($fournisseur->getDevisevente());
            $produit->setFournisseur($fournisseur);
            $produit->setTauxTVA(0.2);
            $produit->setTauxAchat($produitExcel['tauxAchat']);
            $produit->setDevisevente($this->em->getRepository('AppBundle:Monnaie')->find(3));

        }        
    }

    public function getProduit($index){

        $produit = [];

        $produit['quantite'] =  intval($this->objPHPExcel->getSheet(0)->getCell('A'.$index)->getValue());
        $produit['description'] =  $this->objPHPExcel->getSheet(0)->getCell('B'.$index)->getValue();
        $produit['prixachatht'] =  floatval($this->objPHPExcel->getSheet(0)->getCell('H'.$index)->getValue());
        $produit['fraisapproche'] =  floatval($this->objPHPExcel->getSheet(0)->getCell('N'.$index)->getCalculatedValue());
        $produit['tauxAchat'] =  floatval($this->objPHPExcel->getSheet(0)->getCell('P'.$index)->getCalculatedValue());
        $uplift =  floatval($this->objPHPExcel->getSheet(0)->getCell('Q'.$index)->getCalculatedValue());
        $produit['marge'] = floatval(1-1/(1+$uplift));

        $rangeMONNAIE= $this->objPHPExcel->getNamedRange('MONNAIE')->getRange();


        $produit['deviseachat'] =  $this->objPHPExcel->getSheet(0)->getCell($rangeMONNAIE)->getValue();
        $produit['prixunitaire'] =  floatval($this->objPHPExcel->getSheet(0)->getCell('C'.$index)->getCalculatedValue());
        $produit['metier'] =  devisExcel::getMetierId($this->objPHPExcel->getSheet(0)->getCell('F'.$index)->getValue());
        $produit['typeproduit'] =  devisExcel::getTypeProduitId($this->objPHPExcel->getSheet(0)->getCell('E'.$index)->getValue());
        $produit['fournisseur'] =  devisExcel::getFournisseurId($this->objPHPExcel->getSheet(0)->getCell('G'.$index)->getValue());

        return $this->produits[] = $produit;
    }
    public function isProduit($index){
    	return (($this->objPHPExcel->getSheet(0)->getCell('C'.$index)->getDataType()=='n') || ($this->objPHPExcel->getSheet(0)->getCell('C'.$index)->getDataType()=='f'));
    }

    public function getPremierindexproduit(){
        return $this->premierindexproduit;
    }

    public function getDernierindexproduit(){
        return $this->dernierindexproduit;
    }

    public function getClient(){
    	return $this->client;
    }

    public function getCommercial(){
        return $this->commercial;
    }

    public function getProduits(){
        return $this->produits;
    }

    public function getTravailLivraison(){
        return $this->TravailLivraison;
    }

    public function getTravailAvantVente(){
        return $this->TravailAvantVente;
    }

    public function getTravailCommercial(){
        return $this->TravailCommercial;
    }

    public function getTravailImport(){
        return $this->TravailImport;
    }

    private static function getMetierId($metier){
        switch ($metier) {
            case "Poste de Travail":
               return 4;
                break;
            case "Périphérique":
                return 3;
                break ;
            case "Solution":
                return 1;
                break;
            default:
                return 4;
        }
    }   

    private static  function getTypeProduitId($typeproduit){
        switch ($typeproduit) {
            case "Logiciel":
               return 2;
                break;
            case "Matériel":
                return 3;
                break ;
            case "Service":
                return 4;
                break;
            case "Service MD":
                return 1;
                break;
            default:
                return 4;
        }
    }   

    private static  function getFournisseurId($fournisseur){
        switch ($fournisseur) {
            case "Dell":
               return 1;
                break;
            case "HP inc":
                return 4;
                break ;
            case "Lenovo":
                return 5;
                break ;
            case "Local":
                return 9;
                break ;
            case "Microsoft":
                return 6;
                break ;
            case "Profil global":
                return 1;
                break ;
            case "Ricoh":
                return 7;
                break ;
            default:
                return 1;
        }
    }


    private static  function getTravailLivraisonId($travail){
        switch ($travail) {
            case "Aucune":
               return 1;
                break;
            case "Monosite":
                return 4;
                break ;
            case "Multisite":
                return 5;
                break;
            case "Déploiement":
                return 6;
                break;
            default:
                return 4;
        }
    }  


    private static  function getTravailImportId($travail){
        switch ($travail) {
            case "Local":
               return 1;
                break;
            case "International":
                return 3;
                break ;
            default:
                return 3;
        }
    }  


    private static  function getTravailCommercialId($travail){
        switch ($travail) {
            case "Null":
               return 1;
                break;
            case "Moyen":
                return 3;
                break ;
            case "Elevé":
                return 5;
                break;
            default:
                return 3;
        }
    }  


    private static  function getTravailAvantVenteId($travail){
        switch ($travail) {
            case "Null":
               return 1;
                break;
            case "Moyen":
                return 2;
                break ;
            case "Elevé":
                return 5;
                break;
            default:
                return 2;
        }
    }  


    public function getMarge(){
    return 1-($this->produits['prixachatht']*$this->produits['tauxAchat']*(1+$this->produits['fraisapproche']))/($this->produits['prixunitaire']);
    }
}