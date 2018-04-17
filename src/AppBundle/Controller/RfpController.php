<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lot;
use AppBundle\Entity\Rfp;
use AppBundle\Form\RfpType;
use Doctrine\Common\Collections\ArrayCollection;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

/**
 * Rfp controller.
 *
 * @Route("rfp")
 */
class RfpController extends Controller
{
    /**
     * Lists all rfp entities.
     *
     * @Route("/", name="rfp_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rfps = $em->getRepository('AppBundle:Rfp')->findAll();

        return $this->render('rfp/index.html.twig', array(
            'rfps' => $rfps,
        ));
    }

    /**
     * Creates a new rfp entity.
     *
     * @Route("/new", name="rfp_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $rfp = new Rfp();
        $rfp->setDate(new \DateTime());
        $form = $this->createForm('AppBundle\Form\RfpType', $rfp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rfp);
            $em->flush();

            return $this->redirectToRoute('rfp_show', array('id' => $rfp->getId()));
        }

        return $this->render('rfp/edit.html.twig', array(
            'rfp' => $rfp,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a rfp entity.
     *
     * @Route("/{id}", name="rfp_show")
     * @Method("GET")
     */
    public function showAction(Rfp $rfp)
    {
        $deleteForm = $this->createDeleteForm($rfp);

        return $this->render('rfp/show.html.twig', array(
            'rfp' => $rfp,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", defaults={"id" = null}, name="rfp_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Rfp $rfp)
    {
        $originalLots = new ArrayCollection();

        foreach ($rfp->getLots() as $lot) {
            $originalLots->add($lot);
        }

        $form = $this->createForm(RfpType::class, $rfp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($originalLots as $lot) {
                if (false === $rfp->getLots()->contains($lot)) {
                    $em->remove($lot);
                }
            }

            $em->persist($rfp);
            $em->flush();
            return $this->redirectToRoute('rfp_index');
        }

        return $this->render('rfp/edit.html.twig', array(
            'rfp' => $rfp,
            'form' => $form->createView(),
        ));
    }


    /**
     *
     * @Route("/{id}/acte", name="rfp_act")
     * @Method("GET")
     */
    public function actEngagementAction(Request $request, Rfp $rfp)
    {
        $dompdf = new Dompdf();
        $modele = $rfp->getModele() ? $rfp->getModele()->getId() : 1;
        $html = $this->render("rfp/$modele/act.html.twig", ['rfp' => $rfp])->getContent();
        $dompdf->loadHtml($html);

        $dompdf->render();
        $filename =  $this->getParameter('repertoire_temp')."/".$rfp->getId().".pdf";
        file_put_contents($filename, $dompdf->output());

        return new BinaryFileResponse($filename);
    }


    /**
     *
     * @Route("/{id}/declaration", name="rfp_declaration_honor")
     * @Method("GET")
     */
    public function declarationHonorAction(Request $request, Rfp $rfp)
    {
        $dompdf = new Dompdf();
        $modele = $rfp->getModele() ? $rfp->getModele()->getId() : 1;
        $html = $this->render("rfp/$modele/declaration_honor.html.twig", ['rfp' => $rfp])->getContent();
        $dompdf->loadHtml($html);

        $dompdf->render();
        $filename =  $this->getParameter('repertoire_temp')."/".$rfp->getId().".pdf";
        file_put_contents($filename, $dompdf->output());

        return new BinaryFileResponse($filename);
    }

    /**
     * Deletes a rfp entity.
     *
     * @Route("/{id}", name="rfp_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Rfp $rfp)
    {
        $form = $this->createDeleteForm($rfp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rfp);
            $em->flush();
        }

        return $this->redirectToRoute('rfp_index');
    }

    /**
     * Creates a form to delete a rfp entity.
     *
     * @param Rfp $rfp The rfp entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Rfp $rfp)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rfp_delete', array('id' => $rfp->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



}
