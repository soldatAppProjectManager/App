<?php

namespace AppBundle\Controller;

use DateTime;

use AppBundle\Entity\Parametres;
use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\Metier;
use AppBundle\Entity\TypeProduit;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\Devis;
use AppBundle\Entity\Commercial;
use AppBundle\Entity\Client;
use AppBundle\Entity\TermeCommercial;
use AppBundle\Entity\Entete;
use AppBundle\Entity\BonDeCommandeClient;
use AppBundle\Entity\TermesBCRelation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use AppBundle\Form\BonDeCommandeClientFormType;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/BonDeCommandeClient")
 */
class BonDeCommandeClientController extends Controller
{
    /**
     * @Route("", name="BonDeCommandeClient_list")
     */
    public function indexAction(Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/index.html.twig',array('BonDeCommandeClient' => $BonDeCommandeClient));
    }

    /**
     * @Route("/voir/{id}", name="BonDeCommandeClient_voir")
     */
    public function voirAction($id,Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);

        $statuts = $this->getDoctrine()
                        ->getRepository('AppBundle:statutProduit')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/voir.html.twig',array('BonDeCommandeClient' => $BonDeCommandeClient,
            'statuts' => $statuts
            ));
    }


    /**
     * @Route("/profitabilite/{id}", name="BonDeCommandeClient_voirprofitabilite")
     */
    public function voirProfitabiliteAction($id,Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/profitabilite.html.twig',array( 'BonDeCommandeClient' => $BonDeCommandeClient,

                                                                    ));
    }

    /**
     * @Route("/create/{id}", name="BonDeCommandeClient_create")
     */
    public function createAction($id,Request $request)
    {
        $BonDeCommandeClient = new BonDeCommandeClient;

        $devis = $this->getDoctrine()
                        ->getRepository('AppBundle:Devis')
                        ->find($id);

        $form = $this->createForm(BonDeCommandeClientFormType::class,$BonDeCommandeClient);  

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $file= $form['Fichier']->getData();



            $em = $this->getDoctrine()->getManager();

            $BonDeCommandeClient->setCommercial($devis->getCommercial());
            $BonDeCommandeClient->setContact($devis->getDestinataire());
            $BonDeCommandeClient->setClient($devis->getClient());
            $BonDeCommandeClient->setDevis($devis);
            $BonDeCommandeClient->setFichier($file);
            $BonDeCommandeClient->setVerrouille(false);


            $file->move(
                $this->getParameter('repertoire_bcclient'),
                $BonDeCommandeClient->getFichier()
                );

            $i=0;

            $statut = $this->getDoctrine()
                            ->getRepository('AppBundle:statutProduit')
                            ->find(7);            

            foreach ($devis->getProduits() as $produit) {
                $produitBC = new ProduitBC;
                $produitBC->deProduitDevis($produit);
                $produitBC->setNumero(++$i);
                $produitBC->setBonDeCommandeClient($BonDeCommandeClient);
                $produitBC->setStatut($statut);
                $em->persist($produitBC);
            }


            foreach ($devis->getTermes() as $terme) {
                $BonDeCommandeClient->addTerme($terme);
            }
            
            $em->persist($BonDeCommandeClient);


            $em->flush();

            $this->addFlash('notice','BonDeCommandeClient Ajouté');

            return $this->redirectToRoute('BonDeCommandeClient_list');
        }

        return $this->render('BonDeCommandeClient/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/delete/{id}", name="BonDeCommandeClient_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);

        return $this->render('BonDeCommandeClient/delete.html.twig',array('id'=> $id,'BonDeCommandeClient' => $BonDeCommandeClient));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="BonDeCommandeClient_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($BonDeCommandeClient);
        $em->flush();

        $this->addFlash('notice','BonDeCommandeClient Effacé');

        return $this->redirectToRoute('BonDeCommandeClient_list',array('id'=> $id));
    }

    /**
     * @Route("/verrouille/{source}/{id}", name="BonDeCommandeClient_verrouille")
     */
    public function verrouilleAction($id,$source,Request $request)
    {
        // replace this example code with whatever you need
       $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $BonDeCommandeClient->setVerrouille(!$BonDeCommandeClient->getVerrouille()) ;

        $em->persist($BonDeCommandeClient);
        $em->flush();
        
        $message = $BonDeCommandeClient->getVerrouille() ? 'BonDeCommandeClient verrouillé' : 'BonDeCommandeClient déverrouillé';

        $this->addFlash('notice',$message);

        if ($source == "index") return $this->redirectToRoute('BonDeCommandeClient_list');
        else return $this->redirectToRoute('BonDeCommandeClient_voir',array('id' => $BonDeCommandeClient->getId()));
    }


    /**
     * @Route("/edit/{id}", name="BonDeCommandeClient_edit")
     */
    public function editAction($id,Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);

        $form = $this->createForm(BonDeCommandeClientFormType::class,$BonDeCommandeClient);  

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            $file= $form['Fichier']->getData();

            if (isset($file)) $file->move(
                $this->getParameter('repertoire_bcclient'),
                $BonDeCommandeClient->getFichier()
                );

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice','BonDeCommandeClient Mis à jour');

            return $this->redirectToRoute('BonDeCommandeClient_voir',array('id' => $BonDeCommandeClient->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/edit.html.twig',array('form' => $form->createView(),'BonDeCommandeClient' => $BonDeCommandeClient));
    }

        /**
         *
         * @Route("/majDevis", name="maj_bc")
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


    /**
     * @Route("/fichier/{id}", name="BonDeCommandeClient_fichier")
     */
    public function fichierAction($id,Request $request)
    {
        $BonDeCommandeClient = clone $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeClient')
                        ->find($id);

        $file =  $this->getParameter('repertoire_bcclient')."/".$BonDeCommandeClient->getFichier();

        $response = new BinaryFileResponse($file);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $BonDeCommandeClient->getFichier()
        );        

        return $response;
    }


}
