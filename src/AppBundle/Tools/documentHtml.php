<?php

namespace AppBundle\Tools;

use DateTime;
use DateInterval;
use AppBundle\Entity\Devis;
use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\TermeCommercial;
use IntlDateFormatter;
use Doctrine\Common\Collections\ArrayCollection;

class documentHtml
{

    private $fond;
    private $bordures;
    private $logo;
    private $type;
    private $numero;
    private $tableauDeProduits;
    private $tableauDentete;
    private $introduction;
    private $piedDePage;
    private $tableauClient;
    private $tableauTotaux;
    private $tableauDesTermes;
    private $signature;
    private $styleHeader;
    private $body;

    public function __construct($devis,$logo){

    	$this->setTableauDeProduits($devis);
    	$this->setTableauDentete($devis,$logo);
    	$this->setIntroduction($devis);
    	$this->setPiedDePage($devis);
    	$this->setTableauClient($devis);
    	$this->setTableauDesTermes($devis);
    	$this->setTableauTotaux($devis);
    	$this->setSignature($devis);
    	$this->setBody();
    	$this->setStyleHeader();
    }

    /*#############################setters########################*/

    public function setFond($couleur){    	
    	$this->fond = $couleur;
    }

    public function setBordures($bordures){    	
    	$this->fond = $bordures;
    }

    public function setLogo($logo){    	
    	$this->fond = $logo;
    }

    public function setType($type){    	
    	$this->fond = $type;
    }

    public function setNumero($numero){    	
    	$this->fond = $numero;
    }


    public function setStyleHeader(){

        $this->styleHeader =<<<EOD
<head>
<meta charset='utf-8'>
  
  <title>Printed document</title>
  
  <style type="text/css">




	        * {
	        font-size: 70%;
            font-family: sans-serif;

	        }



			@page {
			  margin: 0.2cm;
			}
			



			body {
			  margin-top: 2.5cm;
			  margin-bottom: 2cm;
				margin-left: .5cm;
				margin-right: .5cm;
				text-align: justify;
			}
			
			div.header,
			div.footer {
			  position: fixed;
			  width: 100%;
			  border: 0px solid #888;
			  overflow: hidden;
			  padding: 0.1cm;
				text-align: center;

			}
			
			div.header {
			  top: 0cm;
			  left: 0cm;
			  border-bottom-width: 0px;
			  height: 2.5cm;
			}

	
			div.footer {
			  bottom: 0cm;
			  left: 0cm;
			  border-top-width: 0px;
			  height: 2cm;
			}


			table {
				border-collapse: collapse;
			}

			table.entete{
				margin: 0 auto;
				
			}


			table.produits{
				margin: 0 auto;
		
			}

			table.totaux{
				width:60%;
				margin-right: 0px;
				margin-left: auto;
				page-break-inside: avoid;			
			}

			table.signature{
				margin-right: auto;
				margin-left: 0px;			
			}

			table.produits tbody tr:nth-child(odd){
				background-color: #F4F4F4;
			}

	        table.entete th
	        {
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;	        	
	        }

	        th.numero {
	        	width: 200px;
	        	text-align: center;
	    	}	        

	        table.client th
	        {
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;	        	
	        }	        

	        table.introduction th
	        {
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;       	
	        }

	        table.produits{
	        	width: 100%;
    			table-layout: fixed;
	        }

	        table.produits th
	        {
	        	font-weight: 1000;
	        	font-size: 90%;
	        	border-bottom: 1.5px solid #D22420;
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;
	            color : #545454;
				background-color: #B7B7B7;
				height:20px;
	        }

	        table.produits td:nth-child(4n+1){width: 8%; text-align: center;}
	        table.produits th:nth-child(4n+1){width: 8%; text-align: center;}

	        table.produits td:nth-child(4n+2){width: 65%}
	        table.produits th:nth-child(4n+2){width: 65%}

	        table.produits td:nth-child(4n+3){
	        	width: 15%;
	        	font-weight: 1000;
	        	font-size: 80%;	   
	        }
	        table.produits th:nth-child(4n+3){
	        	width: 15%;
	        	font-weight: 1000;
	        	font-size: 80%;
			}
	                
	        table.produits td:nth-child(4n+4){
	        	width: 15%
	        	font-weight: 1000;
	        	font-size: 80%;
	        	text-align: right;
	        }
	        table.produits th:nth-child(4n+4){
	        	width: 15%
	        	font-weight: 1000;
	        	font-size: 80%;
	        	text-align: right;
	        }

	        table.produits td
	        {
	        	border-bottom: 2px solid #C8C8C8;
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;	        	
	        }

	        table.totaux tr:last-child td
	        {
	        	border-bottom: 0.5px solid #D22420;
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;
	        }

	        table.totaux th
	        {
	        	font-weight: 1000;
	        	font-size: 90%;
	        	border-bottom: 2px solid #C8C8C8;
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;
	            color : #545454;
				background-color: #B7B7B7;
	        }

	        table.totaux td
	        {
	        	border-bottom: 2px solid #C8C8C8;
	            border-left: 0; 
	            border-right: 0; 
	            border-top: 0;
	        }

	        table.signature {
	        		text-align:right;
				    margin-left: 400px;
				    margin-right: 0;
	        }

	       	table.piedDePage {
       			border-left:2.5px solid #D22420;
 	        	text-align:center;
 	        	width:100%;			
	        }


	        </style>
	    	</head>
EOD;
}

    public function setTableauDentete($devis,$logo){
    	$this->tableauDentete = "
        <div class=\"header\" >
            <table class=\"entete\">
                <tr>
                    <td rowspan=\"4\" style=\"vertical-align: top;\">
                        <img src=".$logo." height=\"50\" width=\"292\">
                    </td>
                    <th class=\"numero\" rowspan=\"4\">
                        Devis n°".$devis->getReference()."
                    </th>
                    <th>
                         Gestionnaire :
                    </th>                   
                    <td>
                        ".$devis->getCommercial()."
                    </td>
                </tr>
                <tr>
                    <th>
                        Email:
                    </th>                   
                    <td>
                        ".$devis->getCommercial()->getEmail()."
                    </td>
                </tr>
                <tr>
                    <th>
                        Telephone :
                    </th>                   
                    <td>
                         ".$devis->getCommercial()->getTelephone()."
                    </td>
                </tr>
                <tr>
                    <th>
                        Valide jusqu'au
                    </th>                   
                    <td>
                         ".$devis->getDatecreation()->add(new DateInterval("P".$devis->getValidite()."D"))->format("d/m/Y")."
                    </td>
                </tr>
            </table>
        </div>";
	}


    public function setTableauClient($devis){

    	$formatter = new IntlDateFormatter(
						    'fr_FR',
						    IntlDateFormatter::FULL,
						    IntlDateFormatter::NONE);

    	$aujourdhui = new DateTime("now");
        $validite = $aujourdhui->add(new DateInterval('P30D'));

    	setlocale(LC_ALL, 'fr_FR@euro', 'fr_FR', 'fra_fra');

    	$this->tableauClient ="
        <table class=\"client\">
	        <tbody>
		        <tr>
			        <th>Objet du devis : </th>
			        <td>&nbsp;</td>
			        <td>".$devis->getTitre()."</td>

		        </tr>
		        <tr>
			        <th>Date : </th>
			        <td>&nbsp;</td>
			        <td>".ucwords($formatter->format(new DateTime("now")))."</td>
		        </tr>
		        <tr>
			        <th>A l'attention de :</th>
			        <td>&nbsp;</td>
			        <td>".$devis->getDestinataire()."</td>
		        </tr>
		        <tr>
			        <th>Pour le compte de :</th>
			        <td>&nbsp;</td>
			        <td>".$devis->getClient()->getNom()."</td>
		        </tr>
	        </tbody>
        </table>";
    }
     
    public function setIntroduction($devis){
        $this->introduction = "
        <table class=\"introduction\">
        <tbody>
        <tr>
        <th></th>
        </tr>
        <tr>
        <td>".nl2br($devis->getIntroduction()->getTexte())."</td>
        </tr>
        </tbody>
        </table>";
    }

    public function setTableauDeProduits($devis){
        $this->tableauDeProduits = "
	        <table class=\"produits\">
		        <thead>
		        <tr>
			        <th>Qté</th>
			        <th>Désignation</th>
			        <th>P.U. H.T. ".$devis->getProduits()[0]->getDevisevente()->getSymbol()."</th>
			        <th>S.T. H.T. ".$devis->getProduits()[0]->getDevisevente()->getSymbol()."</th>
		        </tr>
		        </thead>        
	        <tbody>";

        $formatNombre = new \NumberFormatter("fr-FR",  \NumberFormatter::PATTERN_DECIMAL, '#,##0.00');
        

        foreach ($devis->getProduits() as $produit) {
	        $termes = "";
	        $garanties = "";

			if (!$produit->getGaranties()->isEmpty()) {
				$garanties .= "<br><strong>Garantie : </strong>";
				foreach ($produit->getGaranties() as $garantie) {
					$garanties .= "<br>".$garantie;
				}
			} 

			if (!$produit->getTermes()->isEmpty()) {
				$garanties .= "<br><strong>Conditions particulières : </strong>";
				foreach ($produit->getTermes() as $terme) {
					$termes .= "<br>".$terme->getCategorie()->getNom()." : ".$terme->getDescription();
				}
			} 

            $this->tableauDeProduits .= "<tr>
            								<td>".$produit->getQuantite()."</td>
            								<td><strong>".ucfirst($produit->getDesignation())." : </strong><br>
            										".nl2br(ucfirst($produit->getDescription())).
            										$garanties.
            										$termes.
            							   "</td>
            								<td>".$formatNombre->format($produit->getPrixVenteHT())." ".$produit->getDevisevente()->getSymbol()."</td>
            								<td>".$formatNombre->format($produit->getSousTotalHT())." ".$produit->getDevisevente()->getSymbol()."</td>
            							</tr>";
        }

        $this->tableauDeProduits .= "</tbody></table>";
     }

     public function setTableauTotaux($devis){

        $formatTouteLettre = new \NumberFormatter("fr_FR", \NumberFormatter::SPELLOUT);
        $formatNombre = new \NumberFormatter("fr-FR",  \NumberFormatter::PATTERN_DECIMAL, '#,##0.00');

        $this->tableauTotaux .="
	    <table class=\"totaux\">
		    <tbody>
			    <tr>
				    <th>Total HT</th>
				    <td>".$formatNombre->format($devis->getTotalHT())." ".$devis->getProduits()[0]->getDevisevente()->getSymbol()."</td>
		        </tr>
		        <tr>
			        <th>Total T.V.A.</th>
			        <td>".$formatNombre->format($devis->getTotalTVA())." ".$devis->getProduits()[0]->getDevisevente()->getSymbol()."</td>
		        </tr>
		        <tr>
			        <th>Total TTC</th>
			        <td>".$formatNombre->format($devis->getTotalTTC())." ".$devis->getProduits()[0]->getDevisevente()->getSymbol()."</td>
		        </tr>
		        <tr>
		        	<td colspan=\"2\"><i>La somme du présent devis est arretée à : <strong>".ucwords($formatTouteLettre->format($devis->getTotalTTC()))." ".$devis->getProduits()[0]->getDevisevente()->getSymbol()." TTC.</strong></i></td>
		        </tr>
	        </tbody>
        </table>";

     }

    public function setTableauDesTermes($devis){
    	$this->tableauDesTermes ="";

        if ($devis->getTermes()) $this->tableauDesTermes .= "<strong><u> Conditions commerciales générales : </u></strong><br><br>";
        $this->tableauDesTermes .= "<table class=\"termes\"><tbody>";
        


		foreach ($devis->getTermes() as $terme){
		
			$this->tableauDesTermes .="<tr>
	        <th>".ucfirst($terme->getCategorie()->getNom())."</th>
	        <td>".ucfirst($terme->getDescription())."</td></tr>";
        }
        $this->tableauDesTermes .= "</tbody></table>";
    }

    public function setSignature($devis){
            $this->signature = "
        <table class=\"signature\">
	        <tbody>
		        <tr>
		        	<td>Cordialement,</td>
		        </tr>
		        <tr>
		        	<th>".$devis->getCommercial()->getCivilite()." ".$devis->getCommercial()->getPrenom()." ".$devis->getCommercial()->getNom()."</th>
		        <tr>
		        	<th>".$devis->getCommercial()->getPoste()."</th>
		        </tr>
	        </tbody>
        </table>";	
    }

    public function setPiedDePage($devis){
         $this->piedDePage = "
        <div class=\"footer\">
        <table class=\"piedDePage\">
        <tbody>
	        <tr>
		        <th>Contacts</th>
		        <th>Informations Juridiques</th>
		        <th>Identifiants de l'entreprise</th>
	        </tr>

	        <tr>
		        <td>".$devis->getPiedDePage()->getAdresse()."</td>
		        <td>Microdata ".$devis->getPiedDePage()->getFormejuridique()."</td>
		        <td>ICE : ".$devis->getPiedDePage()->getIce()."</td>
	        </tr>

	        <tr>
		        <td>".$devis->getPiedDePage()->getTelephone()."</td>
		        <td>Au capital de ".$devis->getPiedDePage()->getCapital()."</td>
		        <td>Patente : ".$devis->getPiedDePage()->getPatente()."</td>
	        </tr>

	        <tr>
		        <td>".$devis->getPiedDePage()->getFax()."</td>
		        <td>Registre de Commerce : ".$devis->getPiedDePage()->getRc()."</td>
		        <td>CNSS : ".$devis->getPiedDePage()->getCnss()."</td>
		        <td></td>
	        </tr>

	        <tr>
		        <td>".$devis->getPiedDePage()->getEmail()."</td>
		        <td></td>	        
		        <td></td>	        
	        </tr>
	        <tr>
		        <td>".$devis->getPiedDePage()->getSiteweb()."</td>
		        <td></td>
		        <td></td>	        
	        </tr>
        </tbody>
        </table>
        <p class=\"page\"></p>
        </div> <div class=\"page-number\"></div>";	
    }

    public function setBody(){

    	$this->body = $this->getTableauDentete().$this->getPiedDePage().$this->getTableauClient()."<br>".$this->getIntroduction()."<br>".$this->getTableauDeProduits()."<br>".$this->getTableauTotaux()."<br><br><br><br><br><br><br><br><br>".$this->getTableauDesTermes()."<br><br><br><br>".$this->getSignature();
    }

    /*#############################getters########################*/

    public function getFond(){
    	return $this->fond;    	
    }
    public function getBordures(){    	
    	return $this->bordures;    	
    }
    public function getLogo(){    	
    	return $this->logo;    	
    }
    public function getType(){    	
    	return $this->type;    	
    }
    public function getNumero(){    	
    	return $this->numero;    	
    }
    public function getTableauDeProduits(){    	
    	return $this->tableauDeProduits;    	
    }
    public function getTableauDentete(){    	
    	return $this->tableauDentete;    	
    }
    public function getIntroduction(){    	
    	return $this->introduction;    	
    }
    public function getPiedDePage(){    	
    	return $this->piedDePage;    	
    }
    public function getTableauClient(){    	
    	return $this->tableauClient;    	
    }
    public function getTableauDesTermes(){    	
    	return $this->tableauDesTermes;    	
    }
    public function getSignature(){    	
    	return $this->signature;    	
    }
    public function getBody(){    	
    	return $this->body;    	
    }
    public function getStyleHeader(){    	
    	return $this->styleHeader;    	
    }

    public function getTableauTotaux(){
    	return $this->tableauTotaux;
    }

    // /*###########################divers methodes########################*/

    public function getHtml(){
    	return "<html lang=\"fr\">".$this->getStyleHeader().$this->getBody()."</html>";
    }
}