<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BonDeReception;
use AppBundle\Entity\ReceptionProduit;
use AppBundle\Entity\Serie;
use AppBundle\Form\BonDeReceptionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Bondereception controller.
 *
 * @Route("reception")
 */
class BonDeReceptionController extends Controller
{
    /**
     * Lists all bonDeReception entities.
     *
     * @Route("/", name="reception_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bonDeReceptions = $em->getRepository('AppBundle:BonDeReception')->findAll();

        return $this->render('bondereception/index.html.twig', array(
            'bonDeReceptions' => $bonDeReceptions,
        ));
    }

    /**
     * Finds and displays a bonDeReception entity.
     *
     * @Route("/{id}", name="reception_show")
     * @Method("GET")
     */
    public function showAction(BonDeReception $bonDeReception)
    {
        return $this->render('bondereception/show.html.twig', array(
            'bonDeReception' => $bonDeReception,
        ));
    }

    /**
     * @Route("/edit/{id}", name="reception_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, BonDeReception $reception)
    {
        /** @var ReceptionProduit $receptionProduit */
        foreach ($reception->getReceptionProduits() as $receptionProduit) {
            $receptionProduit->implodeSerie();
        }

        $form = $this->createForm(BonDeReceptionType::class, $reception);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($reception->getReceptionProduits() as $receptionProduit) {
                foreach ($receptionProduit->getSeries() as $serie) {
                    $em->remove($serie);
                }
                $em->flush();
                $receptionProduit->explodeSerie();
            }
            if ($form->getData()->getFile()->getFile() !== null) {
                $reception->getFile()->upload($this->getParameter('repertoire_bl_fournisseur'));
            }
            $em->persist($reception);
            $em->flush();
            return $this->redirectToRoute('reception_show', ['id' => $reception->getId()]);
        }

        return $this->render('BonDeCommandeFournisseur/reception.html.twig', [
            'bonDeCommandesFournisseur' => $reception->getBonDeCommandeFournisseur(),
            'form' => $form->createView(),
        ]);
    }
}
