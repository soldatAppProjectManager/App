<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BonDeCommandeFournisseur;
use AppBundle\Entity\BonDeReception;
use AppBundle\Entity\ModeleBCF;
use AppBundle\Entity\ProduitDevis;
use AppBundle\Entity\ReceptionProduit;
use AppBundle\Entity\Serie;
use AppBundle\Entity\statutProduit;
use AppBundle\Form\BonDeReceptionType;
use DateTime;

use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\Devis;
use AppBundle\Entity\BonDeCommandeClient;
use AppBundle\Entity\TermesBCRelation;

use Dompdf\Dompdf;
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
 * @Route("/BonDeCommandeFounisseur")
 */
class BonDeCommandeFournisseurController extends Controller
{
    /**
     * @Route("", name="BonDeCommandeFournisseur_list")
     */
    public function indexAction(Request $request)
    {
        $bonDeCommandesFournisseur = $this->getDoctrine()
                        ->getRepository('AppBundle:BonDeCommandeFournisseur')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('BonDeCommandeFournisseur/index.html.twig',array('bonDeCommandesFournisseur' => $bonDeCommandesFournisseur));
    }

    /**
     * @Route("/generate/{id}", name="BonDeCommandeFournisseur_generate")
     */
    public function generateAction(BonDeCommandeClient $bonDeCommandeClient,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $statusCmdFournisseur = $em->getRepository(statutProduit::class)->find(1);

        $basiqueModele = $em->getRepository(ModeleBCF::class)->find(1);

        /** @var ProduitBC $produit */
        foreach ($bonDeCommandeClient->getProduits() as $produit) {
            $bcf = $em->getRepository(BonDeCommandeFournisseur::class)
                ->findOneBy(['bonDeCommandeClient' => $bonDeCommandeClient, 'fournisseur' => $produit->getFournisseur()]);


            if(empty($bcf)) {
                $countBCF = $em->getRepository(BonDeCommandeFournisseur::class)->count();
                $bcf = new BonDeCommandeFournisseur();
                $bcf->setFournisseur($produit->getFournisseur())->setBonDeCommandeClient($bonDeCommandeClient)->setDate(new DateTime());
                $bcf->generateRef($countBCF);
                $bcf->setModele($basiqueModele);
                if(!empty($bcf->getFournisseur()->getModele())) {
                    $bcf->setModele($bcf->getFournisseur()->getModele());
                }
                $em->persist($bcf);
            }

            $produit->setStatut($statusCmdFournisseur);
            $bcf->addProduit($produit);
            $bcf->setCommercial($bonDeCommandeClient->getCommercial());
            $em->flush();
        }

     return $this->redirectToRoute('BonDeCommandeFournisseur_list');
    }

    /**
     * @Route("/apercu_pdf/{id}", name="BonDeCommandeFournisseur_apercu_pdf")
     */
    public function apercuPdfAction(BonDeCommandeFournisseur $bonDeCommandeFournisseur) {
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // $dompdf->loadHtml($devisHtml->getHtml());

        $html = $this->render('BonDeCommandeFournisseur/'.$bonDeCommandeFournisseur->getModele()->getModele(),array('bcf' => $bonDeCommandeFournisseur))->getContent();

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', $bonDeCommandeFournisseur->getModele()->getMiseenpage());

        // Render the HTML as PDF
        $dompdf->render();

        $filename =  $this->getParameter('repertoire_export')."/".$bonDeCommandeFournisseur->getId().".pdf";

        file_put_contents($filename, $dompdf->output());

        return new BinaryFileResponse($filename);
    }

    /**
     * @Route("/reception/{id}", name="BonDeCommandeFournisseur_reception")
     */
    public function receptionAction(BonDeCommandeFournisseur $bonDeCommandeFournisseur, Request $request) {

        $bonDeReception = new BonDeReception();

        /** @var ProduitBC$produit */
        foreach ($bonDeCommandeFournisseur->getProduits() as $produit) {
            if($produit->getResteReception() > 0)
            {
                $recPro = new ReceptionProduit();
                $recPro->setProduit($produit);
                $recPro->setQuantite($produit->getResteReception());
                $bonDeReception->addReceptionProduit($recPro);
            }
        }

        $form = $this->createForm(BonDeReceptionType::class, $bonDeReception);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bonDeReception->setBonDeCommandeFournisseur($bonDeCommandeFournisseur);
            $em = $this->getDoctrine()->getManager();
            if($form->getData()->getFile()->getFile() !== null) {
                $bonDeReception->getFile()->upload($this->getParameter('repertoire_bl_fournisseur'));
            } else {
                $bonDeReception->setFile(null);
            }

            /** @var ReceptionProduit $receptionProduit */
            foreach ($bonDeReception->getReceptionProduits() as $receptionProduit) {
                if($receptionProduit->getQuantite() === 0) {
                    $bonDeReception->removeReceptionProduit($receptionProduit);
                    continue;
                } elseif ($receptionProduit->getQuantite() === $receptionProduit->getProduit()->getQuantite()) {
                    $receptionProduit->getProduit()->setStatut($em->getRepository(statutProduit::class)->find(2));
                } else {
                    $receptionProduit->getProduit()->setStatut($em->getRepository(statutProduit::class)->find(8));
                }

                $nSeries = explode(",", $receptionProduit->numSeries);
                foreach ($nSeries as $ns) {
                    $serie = new Serie();
                    $serie->setNumero($ns);
                    $receptionProduit->addSeries($serie);
                }
            }

            if($bonDeReception->getReceptionProduits()->count() > 0) {
                $em->persist($bonDeReception);
                $em->flush();
                $this->addFlash('notice', 'Bon de réception ajouté');
            } else {
                $this->addFlash('error', 'Bon de réception refusé : aucun produit reçu');
            }

            return $this->redirectToRoute('reception_show',['id' => $bonDeReception->getId()]);
        }

        return $this->render('BonDeCommandeFournisseur/reception.html.twig',[
            'bonDeCommandesFournisseur' => $bonDeCommandeFournisseur,
            'form' => $form->createView(),
        ]);
    }

}
