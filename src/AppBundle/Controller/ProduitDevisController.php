<?php

namespace AppBundle\Controller;
use DateTime;
use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\Devis;
use AppBundle\Entity\Metier;
use AppBundle\Entity\TypeProduit;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\Parametres;
use AppBundle\Form\ProduitDevisFormType;


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
 * @Route("/produitdevis")
 */
class ProduitDevisController extends Controller
{


    /**
     * @Route("/voir/{id}", name="produitdevis_voir")
     */
    public function voirAction($id,Request $request)
    {
        $produitdevis = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitDevis')
                        ->find($id);

        
        // replace this example code with whatever you need
        return $this->render('produitdevis/voir.html.twig',array('produitdevis' => $produitdevis));
    }

    /**
     * @Route("/create/{id}", name="produitdevis_create")
     */
    public function createAction($id,Request $request)
    {
        $produitdevis = new ProduitDevis;
        $devis = $this->getDoctrine()
                        ->getRepository('AppBundle:Devis')
                        ->find($id);
                        
        $produitdevis->setDevis($devis);


        $form = $this->createForm(ProduitDevisFormType::class,$produitdevis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $produitdevis->setDevisevente($this->getDoctrine()
                            ->getRepository('AppBundle:Monnaie')
                            ->find(3));


            $produitdevis->setnumero($devis->getProduits()->count()+1);
            $produitdevis->setReference();

            $em = $this->getDoctrine()->getManager();

            $em->persist($produitdevis);
            $em->flush();

            $this->addFlash('notice','Produit de devis Ajouté');

            return $this->redirectToRoute('devis_apercu',array('id' => $produitdevis->getDevis()->getId()));
        }

        return $this->render('produitdevis/create.html.twig', array(
            'form' => $form->createView(),
            'devis' => $devis
        ));
    }

    /**
     * @Route("/delete/{id}", name="produitdevis_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $produitdevis = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitDevis')
                        ->find($id);

        return $this->render('produitdevis/delete.html.twig',array('id'=> $id,'designation' => $produitdevis->getDesignation(),'devisId' => $produitdevis->getDevis()->getId()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="produitdevis_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
        $produitdevis = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitDevis')
                    ->find($id);

        $devis = $this->getDoctrine()
                    ->getRepository('AppBundle:Devis')
                    ->find($produitdevis->getDevis());

        $devis->remonterSuivants($produitdevis);
 
        $em = $this->getDoctrine()->getManager();

        $em->remove($produitdevis);
        $em->flush();

        $this->addFlash('notice','Produit de devis Effacé');

        return $this->redirectToRoute('devis_apercu',array('id' => $devis->getId()));
    }

    /**
     * @Route("/edit/{id}", name="produitdevis_edit")
     */
    public function editAction($id,Request $request)
    {
        $produitdevis = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitDevis')
                        ->find($id);

        $devis = $this->getDoctrine()
                        ->getRepository('AppBundle:Devis')
                        ->find($produitdevis->getDevis());                        

        $form = $this->createForm(ProduitDevisFormType::class,$produitdevis);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            //$produitdevis->mettreAJourTauxAchat();
            $produitdevis->setReference();

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice','Produit de devis Mis à jour');



            return $this->redirectToRoute('devis_apercu',array(   'id' => $devis->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('produitdevis/edit.html.twig',array(   'form' => $form->createView(),
                                                                    'devis' => $devis,
                                                                    'produit' => $produitdevis));
    }

    /**
     *
     * @Route("/majDevis", name="maj_devis")
     * @Method("POST")
     */
    public function updateQuantityAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $produit = $this->getDoctrine()->getRepository('AppBundle:ProduitDevis')->find($request->request->get('produit'));
            
            $F = new \NumberFormatter("fr_FR", \NumberFormatter::SPELLOUT);
            $F->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);

            $F2 =new \NumberFormatter("en-US",  \NumberFormatter::PATTERN_DECIMAL, '#,##0.00');

            $produit->setQuantite($request->request->get('quantite'));

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            return new JsonResponse(array(
                'code'                  => 1,
                'prixdevente'           => $F2->format($produit->getPrixVenteHT()),
                'devise'                => $produit->getDeviseachat()->getCode(),
                'sousTotalHT'           => $F2->format($produit->getSousTotalHT()),
                'totalHT'               => $F2->format($produit->getDevis()->getTotalHT()),
                'totalTVA'              => $F2->format($produit->getDevis()->getTotalTVA()),               
                'totalTTC'              => $F2->format($produit->getDevis()->getTotalTTC()),
                'totalEntouteLettre'    => $F->format($produit->getDevis()->getTotalTTC())
            ));

        } 
        else return $this->redirectToRoute('homepage');
        
    }


    /**
     *
     * @Route("/majProduitPrixAchat", name="maj_produit_prixachat")
     * @Method("POST")
     */
    public function majProduitPrixAchatAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $produit = $this->getDoctrine()->getRepository('AppBundle:ProduitDevis')->find($request->request->get('produit'));
            
            $F = new \NumberFormatter("fr_FR", \NumberFormatter::SPELLOUT);
            $F->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);

            $F2 =new \NumberFormatter("en-US",  \NumberFormatter::PATTERN_DECIMAL, '#,##0.00');

            $produit->setPrixachatht($request->request->get('prixachat'));

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            return new JsonResponse(array(
                'code'                  => 1,
                'prixdevente'           => $F2->format($produit->getPrixVenteHT()),
                'devise'                => $produit->getDeviseachat()->getCode(),
                'sousTotalHT'           => $F2->format($produit->getSousTotalHT()),
                'totalHT'               => $F2->format($produit->getDevis()->getTotalHT()),
                'totalTVA'              => $F2->format($produit->getDevis()->getTotalTVA()),               
                'totalTTC'              => $F2->format($produit->getDevis()->getTotalTTC()),
                'totalEntouteLettre'    => $F->format($produit->getDevis()->getTotalTTC())
            ));

        } 
        else return $this->redirectToRoute('homepage');
    }


    /**
     *
     * @Route("/fromcatalogue", name="produitdevis_fromcatalogue")
     * @Method("POST")
     */
    public function fromCatalogueAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->request->get('id');
            // $em = $this->getDoctrine()->getManager();

            $ProduitDevis = $this->getDoctrine()->getRepository('AppBundle:ProduitDevis')->find($id);

            $encoders = array(new JsonEncoder());

            $normalizer = new ObjectNormalizer();

            $normalizer->setIgnoredAttributes(array('devis'));


            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            
            $normalizers = array($normalizer);

            $serializer = new Serializer($normalizers, $encoders);

            // dump($ProduitDevis); die();

            try {
            $ProduitDevisNormalised=$normalizer->normalize($ProduitDevis);
                
            } catch (Exception $e) {
                dump($e); die();
            }

            return new JsonResponse(array(
                'code'                  => 1,
                'produitdevis'           => $ProduitDevisNormalised
            ));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

}
