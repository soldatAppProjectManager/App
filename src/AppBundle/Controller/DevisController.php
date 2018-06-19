<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AbstractProduit;
use AppBundle\Service\charges;
use AppBundle\Service\CurrencyCollector;
use DateTime;
use DateInterval;
use AppBundle\Entity\Parametres;
use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\Metier;
use AppBundle\Entity\TypeProduit;
use AppBundle\Entity\Monnaie;
use AppBundle\Entity\Devis;
use AppBundle\Entity\Commercial;
use AppBundle\Entity\Client;
use AppBundle\Entity\TermeCommercial;
use AppBundle\Entity\Entete;
use AppBundle\Entity\contact;
use AppBundle\Entity\piedDePage;
use AppBundle\Tools\Excelizer;
use AppBundle\Tools\documentHtml;
use AppBundle\Form\DevisFormType;
use AppBundle\Form\ExcelDevisFormType;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Dompdf\Dompdf;


use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\Query;

use Doctrine\ORM\Query\Expr;


/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/devis")
 */
class DevisController extends Controller
{
    /**
     * @Route("/{archived}", name="devis_list", defaults={"archived"=0},
     *     requirements={"archived" = "0|1"})
     */
    public function indexAction(Request $request, $archived)
    {
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->findBy(['archived' => $archived], ['id' => 'DESC']);

        return $this->render('devis/index.html.twig', [
            'devis' => $devis,
            'user' => $this->getUser(),
            'archived' => $archived
        ]);
    }

    /**
     * @Route("/apercu/{id}", name="devis_apercu")
     */
    public function apercuAction(Devis $devis, Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('devis/apercu.html.twig', array('devis' => $devis));
    }


    /**
     * @Route("/produits/{id}", name="devis_produits")
     */
    public function produitsAction($id, Request $request)
    {
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);


        // replace this example code with whatever you need
        return $this->render('devis/produits.html.twig', array('devis' => $devis));
    }


    /**
     * @Route("/fusions/{id}", name="devis_fusions")
     */
    public function fusionsAction($id, Request $request)
    {
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);


        // replace this example code with whatever you need
        return $this->render('devis/fusions.html.twig', array('devis' => $devis));
    }


    /**
     * @Route("/lignes/{id}", name="devis_lignes")
     */
    public function lignesAction(Devis $devis, Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('devis/lignes.html.twig', array('devis' => $devis));
    }


    // /**
    //  * @Route("/changermarge/{id}", name="devis_changermarge")
    //  */
    // public function changermargeAction($id,Request $request)
    // {
    //     $devis = $this->getDoctrine()
    //                     ->getRepository('AppBundle:Devis')
    //                     ->find($id);

    //     $devis->

    //     // replace this example code with whatever you need
    //     return $this->render('devis/voir.html.twig',array('devis' => $devis));
    // }


    /**
     * @Route("/actualiser/{id}", name="devis_actualisertauxachat")
     */
    public function actualiserTauxAchatAction(Devis $devis, Request $request)
    {
        $this->get(CurrencyCollector::class)->update();
        $devis->mettreAJourTauxAchat();
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        // replace this example code with whatever you need
        return $this->redirectToRoute('devis_voirprofitabilite', ['id' => $devis->getId()]);
    }

    /**
     * @Route("/profitabilite/{id}", name="devis_voirprofitabilite")
     */
    public function voirProfitabiliteAction($id, Request $request)
    {
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);

        $this->get(CurrencyCollector::class)->update();

        // replace this example code with whatever you need
        return $this->render('devis/profitabilite.html.twig', array('devis' => $devis));
    }

    /**
     * @Route("/create", name="devis_create")
     */
    public function createAction(Request $request)
    {
        $devis = new Devis($this->getDoctrine()->getRepository('AppBundle:Devis')->getIncrement(),
            $this->getDoctrine()->getRepository('AppBundle:Monnaie')->find(3),
            $this->getDoctrine()->getRepository('AppBundle:Parametres')->find(1)->getValeur(),
            $this->getDoctrine()->getRepository('AppBundle:Parametres')->find(2)->getValeur(),
            $this->getDoctrine()->getRepository('AppBundle:Parametres')->find(3)->getValeur(),
            $this->getUser());

        #---------------------------------------#
        #---------------create Form-------------#
        #---------------------------------------#
        $form = $this->createForm(DevisFormType::class, $devis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($devis);
            $em->flush();

            $this->addFlash('notice', 'Devis Ajouté');

            return $this->redirectToRoute('produitdevis_create', array('id' => $devis->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('devis/create.html.twig', array(
            'form' => $form->createView(),
            'devis' => $devis,
            'travailminimum' => $this->get(charges::class)->getMinimumCharges(),
            'travailmaximum' => $this->get(charges::class)->getMaximumCharges()
        ));
    }

    /**
     * @Route("/delete/{id}", name="devis_delete")
     */
    public function deleteAction($id, Request $request)
    {
        // replace this example code with whatever you need
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);

        return $this->render('devis/delete.html.twig', array('id' => $id, 'devis' => $devis));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="devis_delete_confirmed")
     */
    public function deleteConfirmedAction($id, Request $request)
    {
        // replace this example code with whatever you need
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($devis);
        $em->flush();

        $this->addFlash('notice', 'Devis Effacé');

        return $this->redirectToRoute('devis_list');
    }

    /**
     * @Route("/edit/{id}", name="devis_edit")
     */
    public function editAction($id, Request $request)
    {
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);

        #---------------------------------------#
        #---------------create Form-------------#
        #---------------------------------------#
        $form = $this->createForm(DevisFormType::class, $devis);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice', 'Devis Mis à jour');

            return $this->redirectToRoute('devis_apercu', array('id' => $devis->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('devis/edit.html.twig', array(
            'form' => $form->createView(),
            'devis' => $devis,
            'travailminimum' => $this->get(charges::class)->getMinimumCharges(),
            'travailmaximum' => $this->get(charges::class)->getMaximumCharges()
        ));
    }


    /**
     * @Route("/copie/{id}", name="devis_copie")
     */
    public function copieAction(Devis $devisaCloner, Request $request)
    {
        $devis = clone $devisaCloner;

        $now = new DateTime("now");

        $devis->setTitre("Copie de : " . $devisaCloner->getTitre());
        $devis->setNumero($this->getDoctrine()->getRepository('AppBundle:Devis')->getIncrement());
        $devis->setNumversion("0");
        $devis->setDatecreation($now);
        $devis->setDatemodification($now);
        $devis->setCommercial($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($devis);
        $em->flush();

        $this->addFlash('notice', 'Devis copié');

        return $this->redirectToRoute('devis_apercu', array('id' => $devis->getId()));
    }

    /**
     * @Route("/nouvelleversion/{id}", name="devis_nouvelleversion")
     */
    public function nouvelleVersionAction($id, Request $request)
    {

        $devisaCloner = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);

        $devis = clone $devisaCloner;

        $now = new DateTime("now");

        $devis->setNumversion($this->getDoctrine()->getRepository('AppBundle:Devis')->getIncrementVersion($devisaCloner->getNumero()));
        $devis->setDatemodification($now);
        $devis->setCommercial($this->getUser());
        $devis->setDraft(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($devis);
        $em->flush();

        $this->addFlash('notice', 'Nouvelle version brouillon préparée');

        return $this->redirectToRoute('devis_apercu', array('id' => $devis->getId()));
    }

    /**
     * @Route("/pdf/{id}", name="devis_publier_pdf")
     */
    public function pdfAction($id, Request $request)
    {
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);

        $devis->setDatemodification(new DateTime("now"));
        $devis->setDraft(false);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $html = $this->render('devispdf/' . $devis->getModele()->getModele(), array('devis' => $devis))->getContent();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', $devis->getModele()->getMiseenpage());

        // Render the HTML as PDF
        $dompdf->render();

        $filename = $this->getParameter('repertoire_export') . "/" . $devis->createFileName() . ".pdf";

        $retour = file_put_contents($filename, $dompdf->output());

        // Output the generated PDF to Browser
        $dompdf->stream($devis->createFileName());

        // replace this example code with whatever you need
        return $this->redirectToRoute('devis_apercu', array('id' => $devis->getId()));
    }

    /**
     * @Route("/apercupdf/{id}", name="devis_apercupdf")
     */
    public function apercupdfAction(Devis $devis, Request $request)
    {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // $dompdf->loadHtml($devisHtml->getHtml());

        $items = ['I1', 'I2', ' ', 'I3', 'I4', ' ', 'I5', 'I6', ' ', 'I7', ' '];
        $html = $this->render('devispdf/' . $devis->getModele()->getModele(), array('devis' => $devis, 'item' => $items))->getContent();

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', $devis->getModele()->getMiseenpage());

        // Render the HTML as PDF
        $dompdf->render();

        $filename = $this->getParameter('repertoire_export') . "/" . $devis->createFileName() . ".pdf";

        file_put_contents($filename, $dompdf->output());

        return new BinaryFileResponse($filename);
    }


    /**
     * @Route("/fichier/{id}", name="devis_fichier")
     */
    public function fichierAction($id, Request $request)
    {
        /** @var Devis $devis */
        $devis = $this->getDoctrine()
            ->getRepository('AppBundle:Devis')
            ->find($id);

        $file = $this->getParameter('repertoire_export') . "/" . $devis->createFileName() . ".pdf";

        $response = new BinaryFileResponse($file);

        // $response->setContentDisposition(
        //     ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        //     $devis->createFileName().".pdf"
        // );        

        return $response;
    }


    /**
     *
     * @Route("/monterProduit", name="monter_produit")
     * @Method("POST")
     */
    public function monterProduitAction(Request $request)
    {
        $produit = null;
        foreach ($request->request->get('ordre') as $key => $value) {
            /** @var AbstractProduit $produit */
            $produit = $this->getDoctrine()
                ->getRepository(AbstractProduit::class)
                ->find($key);

            $produit->setOrdre($value);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('devis_apercu', ['id' => $produit->getDocumentClient()->getId()]);
    }

    /**
     * @Route("/archiver/{id}", name="devis_archive")
     * @Method("GET")
     */
    public function toggleArchiveAction(Devis $devis, Request $request)
    {
        $devis->setArchived(!$devis->getArchived());

        $archived = $request->get('archived');

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('notice', $devis->getArchived() ? 'archivé' : 'désarchivé');
        return $this->redirectToRoute('devis_list', ['archived' => $archived]);
    }

    /**
     *
     * @Route("/charges", name="charges_fetch")
     * @Method("POST")
     */
    public function chargesFetchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $travailimport = $this->getDoctrine()->getRepository('AppBundle:TravailImport')->find($request->request->get('idtravailimport'));
            $travailivraison = $this->getDoctrine()->getRepository('AppBundle:TravailLivraison')->find($request->request->get('idtravailivraison'));
            $travailcommercial = $this->getDoctrine()->getRepository('AppBundle:TravailCommercial')->find($request->request->get('idtravailcommercial'));
            $travailavantvente = $this->getDoctrine()->getRepository('AppBundle:TravailAvantVente')->find($request->request->get('idtravailavantvente'));
            $travailminimum = $this->getDoctrine()->getRepository('AppBundle:Parametres')->find(3)->getValeur();

//            print_r([$travailimport->, $travailivraison, $travailcommercial, $travailavantvente]);die;

            return new JsonResponse(array(
                'code' => 1,
                'travail' => $travailimport->getCharge() + $travailivraison->getCharge() + $travailcommercial->getCharge() + $travailavantvente->getCharge() + $travailminimum,
                'travailminimum' => $travailminimum

            ));

            return $this->redirectToRoute('homepage');


        } else {
            return $this->redirectToRoute('homepage');
        }
    }
}
