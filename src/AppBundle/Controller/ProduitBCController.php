<?php

namespace AppBundle\Controller;
use AppBundle\Entity\BonDeCommandeClient;
use DateTime;
use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\Devis;
use AppBundle\Entity\Metier;
use AppBundle\Entity\TypeProduit;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\Parametres;
use AppBundle\Form\ProduitBCFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/ProduitBC")
 */
class ProduitBCController extends Controller
{
    /**
     * @Route("", name="ProduitBC_list")
     */
    public function indexAction(Request $request)
    {
        $ProduitBC = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitBC')
                        ->findAll();


        // replace this example code with whatever you need
        return $this->render('ProduitBC/index.html.twig',array('ProduitBC' => $ProduitBC));
    }

    /**
     * @Route("/voir/{id}", name="ProduitBC_voir")
     */
    public function voirAction($id,Request $request)
    {
        // dump($id); die();

        $ProduitBC = $this->getDoctrine()
                        ->getRepository('AppBundle:ProduitBC')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('ProduitBC/voir.html.twig',array('ProduitBC' => $ProduitBC));
    }

    /**
     * @Route("/create/{id}", name="ProduitBC_create")
     */
    public function createAction($id,Request $request)
    {
        $ProduitBC = new ProduitBC;

        $form = $this->createForm(ProduitBCFormType::class,$ProduitBC);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $ProduitBC->setDevis($this->getDoctrine()
                            ->getRepository('AppBundle:Devis')
                            ->find($id));

            // devise de vente par defaut dans la premiere version est dirham

            $ProduitBC->setDevisevente($this->getDoctrine()
                            ->getRepository('AppBundle:Monnaie')
                            ->find(3));

            $em = $this->getDoctrine()->getManager();

            $em->persist($ProduitBC);
            $em->flush();

            $this->addFlash('notice','Produit de devis Ajouté');

            return $this->redirectToRoute('devis_voir',array('id' => $ProduitBC->getDevis()->getId()));
        }

        return $this->render('ProduitBC/create.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @Route("/deleteConfirmed/{bcid}/{id}", name="ProduitBC_delete_confirmed")
     */
    public function deleteConfirmedAction($bcid,$id,Request $request)
    {
        // replace this example code with whatever you need
        $ProduitBC = $this->getDoctrine()
                    ->getRepository('AppBundle:ProduitBC')
                    ->find($id);

        $em = $this->getDoctrine()->getManager();

        $em->remove($ProduitBC);
        $em->flush();

        $this->addFlash('notice','Produit BC Effacé');

        return $this->redirectToRoute('BonDeCommandeClient_voir',array('id' => $bcid));
    }

    /**
     * @Route("/edit/{id}", name="ProduitBC_edit")
     */
    public function editAction(ProduitBC $produitBC,Request $request)
    {
        /** @var BonDeCommandeClient $bcc */
        $bcc = $produitBC->getDocumentClient();

        $form = $this->createForm(ProduitBCFormType::class, $produitBC);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $TauxFinancementTresorerie = $this->getDoctrine()
                        ->getRepository('AppBundle:Parametres')
                        ->find(2)->getValeur();

            $now = new DateTime('now');

            $bcc->setDatemodification($now);

            //$ProduitBC->mettreAJourTauxAchat();

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice','Produit de devis Mis à jour');



            return $this->redirectToRoute('BonDeCommandeClient_voir',array(   'id' => $bcc->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('ProduitBC/edit.html.twig',array('form' => $form->createView(),
                                                                    'designation' => $produitBC->getDesignation(),
                                                                    'id' => $produitBC->getDocumentClient()->getId(),
                                                                    'produit' => $produitBC,
                                                                    'deviseSymbol'=>$produitBC->getDeviseachat()->getSymbol(),
                                                                    'deviseTaux'=> $produitBC->getDeviseachat()->getTauxAchat())
        );
    }

    /**
     *
     * @Route("/majBC", name="maj_bc")
     * @Method("POST")
     */
    public function updateQuantityAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $produit = $this->getDoctrine()->getRepository('AppBundle:ProduitBC')->find($request->request->get('produit'));
            
            $F = new \NumberFormatter("fr_FR", \NumberFormatter::SPELLOUT);
            $F->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);

            $F2 =new \NumberFormatter("en-US",  \NumberFormatter::PATTERN_DECIMAL, '#,##0.00');

            $produit->setQuantite($request->request->get('quantite'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            
            return new JsonResponse(array(
                'code'                  => 1,
                'sousTotalHT'           => $F2->format($produit->getSousTotalHT()),
                'totalHT'               => $F2->format($produit->getDevis()->getTotalHT()),
                'totalTVA'              => $F2->format($produit->getDevis()->getTotalTVA()),               
                'totalTTC'              => $F2->format($produit->getDevis()->getTotalTTC()),
                'totalEntouteLettre'    => $F->format($produit->getDevis()->getTotalTTC())
            ));

           return $this->redirectToRoute('homepage');


        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    public function miseAJourDevise($id)
    {
        $monnaie = $this->getDoctrine()
                    ->getRepository('AppBundle:Monnaie')
                    ->find($id);

        $monnaie->recupererTauxBkam();

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('notice','Monnaie Mise à jour');        
        return 0;
    }


        /**
         *
         * @Route("/maj/statut", name="maj_statut")
         * @Method("POST")
         */
        public function updateStatutAction(Request $request)
        {
            if ($request->isXmlHttpRequest()) {

                $produit = $this->getDoctrine()->getRepository('AppBundle:ProduitBC')->find($request->request->get('produit'));
                $statut = $this->getDoctrine()->getRepository('AppBundle:statutProduit')->find($request->request->get('statut'));

                $produit->setStatut($statut);

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return new JsonResponse(array(
                    'code'                  => 1
                ));

               return $this->redirectToRoute('homepage');


            } else {
                return $this->redirectToRoute('homepage');
            }
        }



}
