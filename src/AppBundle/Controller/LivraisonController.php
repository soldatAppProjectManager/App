<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BonDeCommandeClient;
use AppBundle\Entity\Livraison;
use AppBundle\Entity\LivraisonProduit;
use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\statutProduit;
use AppBundle\Form\BonDeReceptionType;
use AppBundle\Form\LivraisonType;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Livraison controller.
 *
 * @Route("livraison")
 */
class LivraisonController extends Controller
{
    /**
     * Lists all livraison entities.
     *
     * @Route("/", name="livraison_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livraisons = $em->getRepository('AppBundle:Livraison')->findAll();

        return $this->render('livraison/index.html.twig', array(
            'livraisons' => $livraisons,
        ));
    }

    /**
     * Finds and displays a livraisonProduit entity.
     *
     * @Route("/{id}", name="livraison_show")
     * @Method("GET")
     */
    public function showAction(Livraison $livraison)
    {
        return $this->render('livraison/show.html.twig', array(
            'livraison' => $livraison,
        ));
    }

    /**
     * @Route("/{id}/edit", name="livraison_edit")
     */
    public function editAction(Livraison $livraison, Request $request)
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice', 'Bon de livraison modifié');
            return $this->redirectToRoute('livraison_index');
        }

        return $this->render('livraison/edit.html.twig', [
            'form' => $form->createView(),
            'livraison' => $livraison,
        ]);
    }

    /**
     * @Route("/{id}/print", name="livraison_print")
     */
    public function printAction(Livraison $livraison, Request $request)
    {
        $dompdf = new Dompdf();
        $html = $this->render('livraison/print.html.twig' ,array('livraison' => $livraison))->getContent();
        $dompdf->loadHtml($html);

        $dompdf->render();

        $filename =  $this->getParameter('repertoire_export')."/bl_".$livraison->getId().".pdf";

        file_put_contents($filename, $dompdf->output());

        return new BinaryFileResponse($filename);
    }


    /**
     * @Route("/livraison/bcc/{id}", name="livraison_bc")
     */
    public function LivraisonAction(BonDeCommandeClient $bcc, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $livraison = new Livraison();
        $livraison->setDate(new \DateTime());
        $livraison->setBonDeCommandeClient($bcc);

        /** @var ProduitBC $produit */
        foreach ($bcc->getProduits() as $produit) {
            if (in_array($produit->getStatut()->getId(), statutProduit::STATUT_A_LIVRER)) {
                $livPro = new LivraisonProduit();
                $livPro->setProduit($produit);
                $livPro->setQuantite($produit->getResteLivraison());
                $livraison->addLivraisonProduit($livPro);
            }
        }

        if($livraison->getLivraisonProduits()->count() === 0) {
            $this->addFlash('error', 'Aucun produit disponible pour livraison');
            return $this->redirectToRoute('livraison_index');
        }

        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var LivraisonProduit $livraisonProduit */
            foreach ($livraison->getLivraisonProduits() as $livraisonProduit) {
                if ($livraisonProduit->getQuantite() === 0) {
                    $livraison->removeLivraisonProduit($livraisonProduit);
                    continue;
                }

                if ($livraisonProduit->getProduit()->getResteLivraison() == $livraisonProduit->getQuantite()) {
                    $livraisonProduit->getProduit()->setStatut($em->getRepository(statutProduit::class)->find(6));
                } else {
                    $livraisonProduit->getProduit()->setStatut($em->getRepository(statutProduit::class)->find(9));
                }
            }

            $livraison->generateRef($em->getRepository(Livraison::class)->count());
            $em->persist($livraison);
            $em->flush();

            $this->addFlash('notice', 'Bon de livraison ajouté');
            return $this->redirectToRoute('livraison_index');
        }

        return $this->render('livraison/create.html.twig', [
            'bcc' => $bcc,
            'form' => $form->createView(),
        ]);

    }

}
