<?php

namespace AppBundle\Controller;
use AppBundle\Entity\AbstractDocumentClient;
use DateTime;
use AppBundle\Entity\ProduitFusion;
use AppBundle\Entity\Devis;
use AppBundle\Entity\Metier;
use AppBundle\Entity\TypeProduit;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\Parametres;
use AppBundle\Form\ProduitFusionFormType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/ProduitFusion")
 */
class ProduitFusionController extends Controller
{
    /**
     * @Route("/voir/{id}", name="ProduitFusion_voir")
     */
    public function voirAction($id,Request $request)
    {
        $ProduitFusion = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitFusion')
                        ->find($id);
        
        // replace this example code with whatever you need
        return $this->render('ProduitFusion/voir.html.twig',array('ProduitFusion' => $ProduitFusion));
    }

    /**
     * @Route("/create/{id}", name="ProduitFusion_create")
     */
    public function createAction(AbstractDocumentClient $devis,Request $request)
    {
        $ProduitFusion = new ProduitFusion;
                        
        $ProduitFusion->setDocumentClient($devis);

        $form = $this->createForm(ProduitFusionFormType::class,$ProduitFusion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            if($form->getData()->getOptionnel()==NULL) $ProduitFusion->setOptionnel(false);

            $em->persist($ProduitFusion);
            $em->flush();

            $this->addFlash('notice','Produit de devis Ajouté');

            return $this->render('ProduitFusion/voir.html.twig',array('ProduitFusion' => $ProduitFusion));
        }

        return $this->render('ProduitFusion/create.html.twig', array(
            'form' => $form->createView(),'id'=> $devis->getId()
        ));
    }

    /**
     * @Route("/add/{id}/{produitid}", name="ProduitFusion_add")
     */
    public function addAction($id,$produitid,Request $request)
    {
       $produitdevis = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitDevis')
                        ->find($produitid);   

        // replace this example code with whatever you need
       $ProduitFusion = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitFusion')
                        ->find($id);

        if($produitdevis->getProduitFusion()== NULL) {
           $produitdevis->setProduitFusion($ProduitFusion);
            $em = $this->getDoctrine()->getManager();
            $em->flush();           
        }
        return $this->redirectToRoute('ProduitFusion_voir',array('id' => $id));
    }

    /**
     * @Route("/remove/{id}/{produitid}", name="ProduitFusion_remove")
     */
    public function removeAction($id,$produitid,Request $request)
    {
       $produitdevis = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitDevis')
                        ->find($produitid);   

        // replace this example code with whatever you need
       $ProduitFusion = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitFusion')
                        ->find($id);

        $produitdevis->setProduitFusion(NULL);
        $em = $this->getDoctrine()->getManager();
        $em->flush();           
        
        return $this->redirectToRoute('ProduitFusion_voir',array('id' => $id));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="ProduitFusion_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
        $ProduitFusion = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitFusion')
                    ->find($id);
 
        $em = $this->getDoctrine()->getManager();

        $em->remove($ProduitFusion);
        $em->flush();

        $this->addFlash('notice','Produit de devis Effacé');

        return $this->redirectToRoute('devis_voir',array('id' => $ProduitFusion->getDevis()->getId()));
    }

    /**
     * @Route("/edit/{id}", name="ProduitFusion_edit")
     */
    public function editAction($id,Request $request)
    {
        $ProduitFusion = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitFusion')
                        ->find($id);                     

        $form = $this->createForm(ProduitFusionFormType::class,$ProduitFusion);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            //$ProduitFusion->mettreAJourTauxAchat();

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice','Produit de devis Mis à jour');


            return $this->redirectToRoute('ProduitFusion_voir',array('id' => $id));
        }

        // replace this example code with whatever you need
        return $this->render('ProduitFusion/edit.html.twig',array(   'form' => $form->createView(),'id' => $id));
    }

}
