<?php

namespace AppBundle\Tools;

use DateTime;

use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Style_NumberFormat;
use \PHPExcel_Style_Border;
use \PHPExcel_Style_Fill;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\BonDeCommandeClient;
use Doctrine\Common\Collections\ArrayCollection;

class RapportExcel 
{

    private $file;

    public function __construct($rapport,$user,$repertoireImport){

        $objPHPExcel =  new PHPExcel();

        $ActiveSheet = $objPHPExcel->setActiveSheetIndex(0);

        $style = array('font' => array('bold' => true,'color' => array('rgb' => 'f2f2f2')));

        //apply the style on column A row 1 to Column B row 1
        $ActiveSheet->getStyle('A1:S1')->applyFromArray($style);

        $ActiveSheet->getStyle("A1:S1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('6d6d6d');
        $ActiveSheet->getStyle("A1:S1")->applyFromArray(array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM,'color' => array('rgb' => '333333')))));

       $ActiveSheet ->setCellValue('A1', 'Nom IC')
                    ->setCellValue('B1', 'Nom client')
                    ->setCellValue('C1', 'N° BC')
                    ->setCellValue('D1', 'Date')
                    ->setCellValue('E1', 'Total CA')
                    ->setCellValue('F1', 'Marge nette totale')
                    ->setCellValue('G1', 'Marge totale %')
                    ->setCellValue('H1', 'Statut')
                    ->setCellValue('I1', 'Etat Recouvrement')
                    ->setCellValue('J1', 'Monnaie d\'achat')
                    ->setCellValue('K1', 'Taux appliqué')
                    ->setCellValue('L1', 'CA Solution')
                    ->setCellValue('M1', 'Marge Solution')
                    ->setCellValue('N1', 'CA Poste de travail')
                    ->setCellValue('O1', 'Marge Poste de travail')
                    ->setCellValue('P1', 'CA périphérique')
                    ->setCellValue('Q1', 'Marge périphérique')
                    ->setCellValue('R1', 'CA service MD')
                    ->setCellValue('S1', 'Marge service MD');
        $i = 2;
        foreach ($rapport as $BonDeCommande) {

            $RevenuPardevise = $BonDeCommande->getRevenuParDevise();

            $maxDevise = reset($RevenuPardevise);

            foreach ($RevenuPardevise as $devise) {
                if ($devise['revenu'] > $maxDevise['revenu']) {
                    $maxDevise = $devise;
                }
            }

            $ActiveSheet->getStyle("A".$i.":S".$i)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '333333')))));


            $ActiveSheet->setCellValue('C'.$i, $BonDeCommande->getNumeroDeBonDeCommandeClient())
                        ->setCellValue('D'.$i, $BonDeCommande->getDatedereception()->format('d/m/Y'));



            $RevenuParMetier = $BonDeCommande->getRevenuParMetier();
            $MargeParMetier = $BonDeCommande->getMargeParMetier();

            if (isset($RevenuParMetier['Infrastructure serveur']['revenu'])){
                $ActiveSheet->setCellValue('L'.$i, $RevenuParMetier['Infrastructure serveur']['revenu']);
            }



            if (isset($BonDeCommande->getMargeParMetier()['Infrastructure serveur']['marge'])){
                $ActiveSheet->setCellValue('M'.$i, $BonDeCommande->getMargeParMetier()['Infrastructure serveur']['marge']);
            }

            if (isset($BonDeCommande->getRevenuParMetier()['Client']['revenu'])){
                $ActiveSheet->setCellValue('N'.$i, $BonDeCommande->getRevenuParMetier()['Client']['revenu']);
            }

            if (isset($BonDeCommande->getMargeParMetier()['Client']['marge'])){
                $ActiveSheet->setCellValue('O'.$i, $BonDeCommande->getMargeParMetier()['Client']['marge']);
            }

            if (isset($BonDeCommande->getRevenuParMetier()['Image']['revenu'])){
                $ActiveSheet->setCellValue('P'.$i, $BonDeCommande->getRevenuParMetier()['Image']['revenu']);
            }

            if (isset($BonDeCommande->getMargeParMetier()['Image']['marge'])){
                $ActiveSheet->setCellValue('Q'.$i, $BonDeCommande->getMargeParMetier()['Image']['marge']);
            }

            if (isset($BonDeCommande->getRevenuParTypeProduit()['Service Microdata']['revenu'])){
                $ActiveSheet->setCellValue('R'.$i, $BonDeCommande->getRevenuParTypeProduit()['Service Microdata']['revenu']);
            }

            if (isset($BonDeCommande->getMargeParTypeProduit()['Service Microdata']['marge'])){
                $ActiveSheet->setCellValue('S'.$i, $BonDeCommande->getMargeParTypeProduit()['Service Microdata']['marge']);
            }


            $ActiveSheet->setCellValue('A'.$i, $BonDeCommande->getCommercial()->getCivilite()." ".$BonDeCommande->getCommercial()->getPrenom()." ".$BonDeCommande->getCommercial()->getNom()." ")
                        ->setCellValue('B'.$i, $BonDeCommande->getClient()->getNom())
                        ->setCellValue('E'.$i, $BonDeCommande->getTotalHT())
                        ->setCellValue('F'.$i, $BonDeCommande->getTauxMargeNette())
                        ->setCellValue('G'.$i, $BonDeCommande->getTauxMargeBrute())
                        ->setCellValue('H'.$i, 'N/A')
                        ->setCellValue('I'.$i, 'Non recouvert')
                        ->setCellValue('J'.$i, $maxDevise['nom'])
                        ->setCellValue('K'.$i, $maxDevise['taux']);


            $ActiveSheet->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $ActiveSheet->getStyle('F'.$i)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));
            $ActiveSheet->getStyle('G'.$i)->getNumberFormat()->applyFromArray(array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00));

            foreach(range('L','S') as $columnID) {
                        $ActiveSheet->getStyle($columnID.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            }

            foreach(range('A','S') as $columnID) {
                $ActiveSheet->getColumnDimension($columnID)->setAutoSize(true);
            }


        $i++;

    }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");

        $filename=$repertoireImport."/".md5(uniqid()).".xlsx";

        //$filename = $repertoireImport."/rapport.xlsx";

        // $filename = str_replace( "/" ,"\\", $filename); 

         // dump($filename); die();


        $objWriter->save($filename);

        $this->file = $filename;
    }

    public function getFichier(){
        return $this->file;
    }
}