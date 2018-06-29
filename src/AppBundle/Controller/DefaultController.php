<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BonDeCommandeClient;
use AppBundle\Entity\BonDeCommandeFournisseur;
use AppBundle\Entity\Devis;
use AppBundle\Entity\Facture;
use AppBundle\Entity\Opportunity;
use AppBundle\Entity\OpportunityStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_USER')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if($this->isGranted('ROLE_DIRECTION')) {
            return $this->redirectToRoute('direction_dashboard');
        }

        $em = $this->getDoctrine()->getManager();
        $opportunities = $em->getRepository(Opportunity::class)->findByStatusCode(OpportunityStatus::NCOURS_CODE);
        $allDevis = $em->getRepository(Devis::class)->findDevisInProgress();
        $devis = [];
        $now = new \DateTime();
        /** @var Devis $d */
        foreach ($allDevis as $d) {
            if($d->getDateFinValidite() > $now) {
                $devis[] = $d;
            }
    }


    $cmds = $em->getRepository(BonDeCommandeClient::class)->findCmdNotDelivered();
    $factures = $em->getRepository(Facture::class)->findAll();
        return $this->render('default/index.html.twig', [
            'opportunities' => $opportunities,
            'allDevis' => $devis,
            'cmds' => $cmds,
            'factures' => $factures
            ]);
    }

    /**
     * @Route("/facture", name="facture_index")
     */
    public function listFactureAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $factures = $em->getRepository(Facture::class)->findBy([], ['id' => 'desc']);


        return $this->render('facture/index.html.twig', ['factures' => $factures]);
    }
}
