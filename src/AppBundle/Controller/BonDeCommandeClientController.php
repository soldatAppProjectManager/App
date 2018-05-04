<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use AppBundle\Entity\ProduitFusion;
use AppBundle\Form\FactureType;
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

use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        return $this->render('BonDeCommandeClient/index.html.twig', array('BonDeCommandeClient' => $BonDeCommandeClient));
    }

    /**
     * @Route("/new", name="BonDeCommandeClient_new")
     */
    public function newAction(Request $request) {

        $ticket = new BonDeCommandeClient();
        $form = $this->createForm('AppBundle\Form\TicketType', $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $ticket->setCreatedBy($this->getUser());
            $ticket->generateRef($em->getRepository(Ticket::class)->getIncrement());
            if ($form->getData()->getFile()->getFile() !== null) {
                $ticket->getFile()->upload($this->getParameter('repertoire_ticket'));
            } else {
                $ticket->setFile(null);
            }
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('ticket_show', array('id' => $ticket->getId()));
        }

        return $this->render('ticket/new.html.twig', array(
            'ticket' => $ticket,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/voir/{id}", name="BonDeCommandeClient_voir")
     */
    public function voirAction($id, Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);

        $statuts = $this->getDoctrine()
            ->getRepository('AppBundle:statutProduit')
            ->findAll();

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/voir.html.twig', array('BonDeCommandeClient' => $BonDeCommandeClient,
            'statuts' => $statuts
        ));
    }


    /**
     * @Route("/profitabilite/{id}", name="BonDeCommandeClient_voirprofitabilite")
     */
    public function voirProfitabiliteAction($id, Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/profitabilite.html.twig', array('BonDeCommandeClient' => $BonDeCommandeClient));
    }

    /**
     * @Route("/create/{id}", name="BonDeCommandeClient_create")
     */
    public function createAction(Devis $devis, Request $request)
    {
        $BonDeCommandeClient = new BonDeCommandeClient;
        $form = $this->createForm(BonDeCommandeClientFormType::class, $BonDeCommandeClient);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var UploadedFile $file */
            $file = $form['Fichier']->getData();
            $em = $this->getDoctrine()->getManager();

            $BonDeCommandeClient->setCommercial($devis->getCommercial());
            $BonDeCommandeClient->setContact($devis->getContact());
            $BonDeCommandeClient->setClient($devis->getClient());
            $BonDeCommandeClient->setDevis($devis);
            $BonDeCommandeClient->setVerrouille(false);

            if (!empty($file)) {
                $BonDeCommandeClient->setFichier($file);
                $file->move(
                    $this->getParameter('repertoire_bcclient'),
                    $BonDeCommandeClient->getFichier()
                );
            }

            $statut = $this->getDoctrine()
                ->getRepository('AppBundle:statutProduit')
                ->find(7);

            $i = 0;
            /** @var ProduitFusion $produitFusion */
            foreach ($devis->getProduitsFusion() as $produitFusion) {
                $produitFusBC = new ProduitFusion();
                $produitFusBC->setDesignation($produitFusion->getDesignation());
                $produitFusBC->setDescription($produitFusion->getDescription());
                $produitFusBC->setQuantite($produitFusion->getQuantite());
                $produitFusBC->setOrdre($produitFusBC->getOrdre());
                $produitFusBC->setDocumentClient($BonDeCommandeClient);
                $produitFusBC->setOptionnel($produitFusion->getOptionnel());
                $produitFusBC->setPrixVenteHT($produitFusion->getPrixVenteHT());

                /** @var ProduitDevis $produit */
                foreach ($produitFusion->getProduits() as $produit) {
                    $produitBC = new ProduitBC;
                    $produitBC->deProduitDevis($produit);
                    $produitBC->setOrdre($produit->getOrdre());
                    $produitBC->setDocumentClient($BonDeCommandeClient);
                    $produitBC->setProduitFusion($produitFusBC);
                    $produitBC->setStatut($statut);
                    $produitFusion->addProduit($produitBC);
                }

                $BonDeCommandeClient->addAbstractProduit($produitFusBC);
            }

            /** @var ProduitDevis $produit */
            foreach ($devis->getProduits() as $produit) {
                if (!$produit->estFusionne()) {
                    $produitBC = new ProduitBC;
                    $produitBC->deProduitDevis($produit);
                    $produitBC->setOrdre($produit->getOrdre());
                    $produitBC->setDocumentClient($BonDeCommandeClient);
                    $produitBC->setStatut($statut);
                    $BonDeCommandeClient->addAbstractProduit($produitBC);
                }
            }

            foreach ($devis->getTermes() as $terme) {
                $BonDeCommandeClient->addTerme($terme);
            }

            $em->persist($BonDeCommandeClient);
            $em->flush();

            $this->addFlash('notice', sprintf('#%d ,Bon de commande client ajouté', $BonDeCommandeClient->getId()));

            return $this->redirectToRoute('BonDeCommandeClient_list');
        }

        return $this->render('BonDeCommandeClient/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/delete/{id}", name="BonDeCommandeClient_delete")
     */
    public function deleteAction($id, Request $request)
    {
        // replace this example code with whatever you need
        $BonDeCommandeClient = $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);

        return $this->render('BonDeCommandeClient/delete.html.twig', array('id' => $id, 'BonDeCommandeClient' => $BonDeCommandeClient));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="BonDeCommandeClient_delete_confirmed")
     */
    public function deleteConfirmedAction($id, Request $request)
    {
        // replace this example code with whatever you need
        $BonDeCommandeClient = $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($BonDeCommandeClient);
        $em->flush();

        $this->addFlash('notice', 'BonDeCommandeClient Effacé');

        return $this->redirectToRoute('BonDeCommandeClient_list', array('id' => $id));
    }

    /**
     * @Route("/verrouille/{source}/{id}", name="BonDeCommandeClient_verrouille")
     */
    public function verrouilleAction($id, $source, Request $request)
    {
        // replace this example code with whatever you need
        $BonDeCommandeClient = $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);


        $em = $this->getDoctrine()->getManager();

        $BonDeCommandeClient->setVerrouille(!$BonDeCommandeClient->getVerrouille());

        $em->persist($BonDeCommandeClient);
        $em->flush();

        $message = $BonDeCommandeClient->getVerrouille() ? 'BonDeCommandeClient verrouillé' : 'BonDeCommandeClient déverrouillé';

        $this->addFlash('notice', $message);

        if ($source == "index") return $this->redirectToRoute('BonDeCommandeClient_list');
        else return $this->redirectToRoute('BonDeCommandeClient_voir', array('id' => $BonDeCommandeClient->getId()));
    }


    /**
     * @Route("/edit/{id}", name="BonDeCommandeClient_edit")
     */
    public function editAction($id, Request $request)
    {
        $BonDeCommandeClient = $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);

        $form = $this->createForm(BonDeCommandeClientFormType::class, $BonDeCommandeClient);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['Fichier']->getData();

            if (isset($file)) $file->move(
                $this->getParameter('repertoire_bcclient'),
                $BonDeCommandeClient->getFichier()
            );

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice', 'BonDeCommandeClient Mis à jour');

            return $this->redirectToRoute('BonDeCommandeClient_voir', array('id' => $BonDeCommandeClient->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeClient/edit.html.twig', array('form' => $form->createView(), 'BonDeCommandeClient' => $BonDeCommandeClient));
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

            $F2 = new \NumberFormatter("en-US", \NumberFormatter::PATTERN_DECIMAL, '#,##0.00');

            $produit->setQuantite($request->request->get('quantite'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return new JsonResponse(array(
                'code' => 1,
                'sousTotalHT' => $F2->format($produit->getSousTotalHT()),
                'totalHT' => $F2->format($produit->getDevis()->getTotalHT()),
                'totalTVA' => $F2->format($produit->getDevis()->getTotalTVA()),
                'totalTTC' => $F2->format($produit->getDevis()->getTotalTTC()),
                'totalEntouteLettre' => $F->format($produit->getDevis()->getTotalTTC())
            ));

            return $this->redirectToRoute('homepage');


        } else {
            return $this->redirectToRoute('homepage');
        }
    }


    /**
     * @Route("/fichier/{id}", name="BonDeCommandeClient_fichier")
     */
    public function fichierAction($id, Request $request)
    {
        $BonDeCommandeClient = clone $this->getDoctrine()
            ->getRepository('AppBundle:BonDeCommandeClient')
            ->find($id);

        $file = $this->getParameter('repertoire_bcclient') . "/" . $BonDeCommandeClient->getFichier();

        $response = new BinaryFileResponse($file);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $BonDeCommandeClient->getFichier()
        );

        return $response;
    }

    /**
     * @Route("/{id}/generate_facture", name="facture_generate")
     */
    public function generateFactureAction(BonDeCommandeClient $bonDeCommandeClient, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Facture $facture */
        $facture = $em->getRepository(Facture::class)->findOneBy(['bonDeCommandeClient' => $bonDeCommandeClient]);
        if ($facture) {
            $this->addFlash('error', 'Une facture a été déjà générée pour ce bon de commande par ' . $facture->getUser());
            return $this->redirectToRoute('BonDeCommandeClient_list');
        }

        $facture = new Facture();
        $facture->setUser($this->getUser());
        $facture->setDate(new DateTime());
        $facture->setBonDeCommandeClient($bonDeCommandeClient);

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $facture->generateRef($em->getRepository(Facture::class)->getIncrement());
            $em->persist($facture);
            $em->flush();
            $this->addFlash('notice', 'Votre facture a été générée avec succès');
            return $this->redirectToRoute('facture_index');
        }

        return $this->render('facture/create.html.twig', [
            'bcc' => $bonDeCommandeClient,
            'form' => $form->createView(),
        ]);

    }
}
