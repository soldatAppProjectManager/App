<?php

namespace AppBundle\Controller;

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
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'opportunities' => $opportunities,
            'allDevis' => $devis
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
