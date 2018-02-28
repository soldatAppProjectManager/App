<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Facture;
use AppBundle\Entity\Paiement;
use AppBundle\Form\PaiementType;
use Dompdf\Dompdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_USER')")
 * @Route("/facture")
 */
class FactureController extends Controller
{
    /**
     * @Route("/", name="facture_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $factures = $em->getRepository(Facture::class)->findBy([], ['id' => 'desc']);

        return $this->render('facture/index.html.twig', ['factures' => $factures]);
    }


    /**
     * @Route("/print_pdf/{id}", name="facture_pdf")
     */
    public function printAction(Facture $facture)
    {
        $dompdf = new Dompdf();
        $html = $this->render('facture/print.html.twig', ['facture' => $facture])->getContent();
        $dompdf->loadHtml($html);

        $dompdf->render();
        $filename = $this->getParameter('repertoire_export') . "/facture_" . $facture->getId() . ".pdf";

        file_put_contents($filename, $dompdf->output());

        return new BinaryFileResponse($filename);
    }


    /**
     * @Route("/{id}/paiements", name="facture_paiements")
     */
    public function paiementsAction(Facture $facture)
    {
        $em = $this->getDoctrine()->getManager();
        $paiements = $em->getRepository(Paiement::class)->findBy(['facture' => $facture], ['id' => 'DESC']);

        return $this->render('facture/paiements.html.twig', ['paiements' => $paiements, 'facture' => $facture]);
    }

    /**
     * @Route("/{id}/paiement/{idPaiement}/edit", name="paiement_edit")
     */
    public function paiementNewAction(Facture $facture, Request $request, $idPaiement = 0)
    {
        $em = $this->getDoctrine()->getManager();

        $paiement = $em->getRepository(Paiement::class)->find($idPaiement);

        if (null === $paiement) {
            $paiement = new Paiement();
            $paiement->setFacture($facture);
        }

        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $paiement->getId()) {
                $paiement->setCreatedAt(new \DateTime());
                $paiement->setCreatedBy($this->getUser());
            }
            if ($form->getData()->getFile()->getFile() !== null) {
                $paiement->getFile()->upload($this->getParameter('repertoire_paiement'));
            } elseif(null === $paiement->getFile()->getId()) {
                    $paiement->setFile(null);
            }

            $em->persist($paiement);
            $em->flush();
            $this->addFlash('notice', 'Votre paiement a été ajouté avec succès');
            return $this->redirectToRoute('facture_paiements', ['id' => $facture->getId()]);
        }

        return $this->render('paiement/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }


}
