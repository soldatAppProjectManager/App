<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\garantie;
use AppBundle\Entity\TermesDevisRelation;
use AppBundle\Form\GarantieFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/garantie")
 */
class garantieController extends Controller
{
    /**
     * @Route("", name="garantie_list")
     */
    public function indexAction(Request $request)
    {
        $garantie = $this->getDoctrine()
                        ->getRepository('AppBundle:garantie')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('garantie/index.html.twig',array('list' => $garantie));
    }

    /**
     * @Route("/voir/{id}", name="garantie_voir")
     */
    public function voirAction($id,Request $request)
    {
        $garantie = $this->getDoctrine()
                        ->getRepository('AppBundle:garantie')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('garantie/voir.html.twig',array('enregistrement' => $garantie));
    }

    /**
     * @Route("/create", name="garantie_create")
     */
    public function createAction(Request $request)
    {
        $garantie = new garantie;

        $form = $this->createForm(GarantieFormType::class,$garantie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($garantie);

            $em->flush();



            $this->addFlash('notice','Garantie Ajoutée');

            return $this->redirectToRoute('garantie_list');

        }

        return $this->render('garantie/create.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/delete/{id}", name="garantie_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $garantie = $this->getDoctrine()
                        ->getRepository('AppBundle:garantie')
                        ->find($id);

        return $this->render('garantie/delete.html.twig',array('id'=> $id,'nom' => $garantie->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="garantie_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $garantie = $this->getDoctrine()
                        ->getRepository('AppBundle:garantie')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($garantie);
        $em->flush();

        $this->addFlash('notice','Garantie Effacée');

        return $this->redirectToRoute('garantie_list');

    }

    /**
     * @Route("/edit/{id}", name="garantie_edit")
     */
    public function editAction($id,Request $request)
    {
        $garantie = $this->getDoctrine()
                        ->getRepository('AppBundle:garantie')
                        ->find($id);

        $form = $this->createForm(GarantieFormType::class,$garantie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $garantie = $em->getRepository('AppBundle:garantie')->find($id);
            $em->flush();

            $this->addFlash('notice','Terme Commercial Mis à jour');

            return $this->redirectToRoute('garantie_list');
        }

        return $this->render('garantie/edit.html.twig',array('form' => $form->createView(),'nom' => $garantie->getNom()));
    }

}
