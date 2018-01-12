<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TravailCommercial;
use AppBundle\Form\TravailCommercialFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/TravailCommercial")
 */
class TravailCommercialController extends Controller
{
    /**
     * @Route("", name="TravailCommercial_list")
     */
    public function indexAction(Request $request)
    {
        $TravailCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailCommercial')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('TravailCommercial/index.html.twig',array('list' => $TravailCommercial));
    }

    /**
     * @Route("/voir/{id}", name="TravailCommercial_voir")
     */
    public function voirAction($id,Request $request)
    {
        $TravailCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailCommercial')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('TravailCommercial/voir.html.twig',array('enregistrement' => $TravailCommercial));
    }

    /**
     * @Route("/create", name="TravailCommercial_create")
     */
    public function createAction(Request $request)
    {
        $TravailCommercial = new TravailCommercial;

        $form = $this->createForm(TravailCommercialFormType::class,$TravailCommercial);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($TravailCommercial);
            $em->flush();

            $this->addFlash('notice','TravailCommercial Ajouté');

            return $this->redirectToRoute('TravailCommercial_list');
        }

        return $this->render('TravailCommercial/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="TravailCommercial_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailCommercial')
                        ->find($id);

        return $this->render('TravailCommercial/delete.html.twig',array('id'=> $id,'nom' => $TravailCommercial->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="TravailCommercial_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailCommercial')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($TravailCommercial);
        $em->flush();

        $this->addFlash('notice','TravailCommercial Effacé');

        return $this->redirectToRoute('TravailCommercial_list');

    }

    /**
     * @Route("/edit/{id}", name="TravailCommercial_edit")
     */
    public function editAction($id,Request $request)
    {
        $TravailCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailCommercial')
                        ->find($id);

        $form = $this->createForm(TravailCommercialFormType::class,$TravailCommercial);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $metier = $em->getRepository('AppBundle:TravailCommercial')->find($id);
            $em->flush();

            $this->addFlash('notice','TravailCommercial Mis à jour');

            return $this->redirectToRoute('TravailCommercial_list');
        }

        return $this->render('TravailCommercial/edit.html.twig',array('form' => $form->createView(),'nom' => $TravailCommercial->getNom()));
    }

}
