<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Opportunity;
use AppBundle\Entity\OpportunityStatus;
use AppBundle\Entity\OpportunityType;
use AppBundle\Entity\OProduct;
use AppBundle\Form\SearchPeriodType;
use AppBundle\Repository\OpportunityRepository;
use AppBundle\Search\PeriodCriteria;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Opportunity controller.
 *
 * @Route("opportunity")
 */
class OpportunityController extends Controller
{
    /**
     * Lists all opportunityType entities.
     *
     * @Route("/", name="opportunity_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $opportunities = $em->getRepository(Opportunity::class)->findAll();

        return $this->render('opportunity/index.html.twig', array(
            'opportunitys' => $opportunities,
        ));
    }

    /**
     * Creates a new opportunity entity.
     *
     * @Route("/edit/{id}", defaults={"id" = null}, name="opportunity_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Opportunity $opportunity = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (null === $opportunity) {
            $opportunity = new Opportunity();
            $opportunity->setStatus($em->getRepository(OpportunityStatus::class)->findOneBy(['code' => 1]));
        }

        $originalProducts = new ArrayCollection();

        foreach ($opportunity->getProducts() as $product) {
            $originalProducts->add($product);
        }

        $form = $this->createForm('AppBundle\Form\OpportunityType', $opportunity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalProducts as $product) {
                if (false === $opportunity->getProducts()->contains($product)) {
                    $em->remove($product);
                }
            }

            $em->persist($opportunity);
            $em->flush();
            return $this->redirectToRoute('opportunity_index');
        }

        return $this->render('opportunity/edit.html.twig', array(
            'opportunity' => $opportunity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a opportunity entity.
     *
     * @Route("/{id}", name="opportunity_show")
     * @Method("GET")
     */
    public function showAction(Opportunity $opportunity)
    {
        $deleteForm = $this->createDeleteForm($opportunity);

        return $this->render('opportunity/show.html.twig', array(
            'opportunity' => $opportunity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a opportunity entity.
     *
     * @Route("/{id}", name="opportunity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Opportunity $opportunity)
    {
        $form = $this->createDeleteForm($opportunity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($opportunity);
            $em->flush();
        }

        return $this->redirectToRoute('opportunity_index');
    }

    /**
     * Creates a form to delete a opportunity entity.
     *
     * @param Opportunity $opportunity The opportunity entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Opportunity $opportunity)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('opportunity_delete', array('id' => $opportunity->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
