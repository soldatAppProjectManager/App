<?php

namespace AppBundle\Controller;

use AppBundle\Tools\Rapport;
use AppBundle\Tools\RapportExcel;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use AppBundle\Form\RapportFormType;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/rapport")
 */
class RapportController extends Controller
{
    /**
     * @Route("", name="Rapport_list")
     */
    public function indexAction(Request $request)
    {

                    
        // replace this example code with whatever you need
        return $this->render('Rapport/index.html.twig');
    }

    /**
     * @Route("/RapportHebdomadaire", name="Rapport_voir")
     */
    public function GenererRapportHebdomadaireAction(Request $request)
    {

         $rapport = $this->getDoctrine()->getRepository('AppBundle:BonDeCommandeClient')->getRapportHebdomadaire($this->getUser());

        // dump($rapport);die();

        // $date = new \DateTime('last monday');

        // $rapport =  $em ->createQueryBuilder()
        //                 ->select('devis', 'commercial','client')
        //                 ->from('AppBundle:Devis', 'devis')
        //                 ->innerJoin('devis.commercial', 'commercial')
        //                 ->innerJoin('devis.client', 'client')
        //                 ->andWhere('devis.datecreation >= :min')
        //                 ->andWhere('devis.datecreation <= :max')
        //                 ->setParameters(array('min' => $date,'max' => new \DateTime("now")))
        //                 ->getQuery()->getResult();

        $rapportExcel = new RapportExcel($rapport,$this->getUser(),$this->getParameter('repertoire_rapportexport'));

        $response = new BinaryFileResponse($rapportExcel->getFichier());

        $response->prepare($request);
        $response->send();

        $fs = new Filesystem();
        $fs->remove($rapportExcel->getFichier());        

        // replace this example code with whatever you need

        return $this->render('Rapport/voir.html.twig',array('rapport' => $rapport,'lundi'=> $date,'numsemaine' => $date->format("W"),'annee' => $date->format("Y")));
    }

    /**
     * @Route("/nonlivre", name="Rapport_nonlivre")
     */
    public function NonLivreAction(Request $request)
    {

         $rapport = $this->getDoctrine()->getRepository('AppBundle:BonDeCommandeClient')->findBCRisqueRetard($this->getUser(),5);
         // $rapport = $this->getDoctrine()->getRepository('AppBundle:BonDeCommandeClient')->findBCRetard($this->getUser(),5);

         // dump($rapport);die();

        // replace this example code with whatever you need

        $devis = $this->getDoctrine()
                        ->getRepository('AppBundle:Devis')
                        ->find(51);


        return $this->render('devispdf/devis1.html.twig',array('devis' => $devis));

        // return  $this->render('Rapport/bcechus.html.twig',array('BonDeCommandeClient' => $rapport));
    }

    /**
     * @Route("/create", name="Rapport_create")
     */
    public function createAction(Request $request)
    {
        $Rapport = new Rapport;

        $form = $this->createForm(RapportFormType::class,$Rapport);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            return $this->redirectToRoute('contact_create');
        }

        return $this->render('Rapport/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
