<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TravailLivraison;
use AppBundle\Form\TravailLivraisonFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/TravailLivraison")
 */
class TravailLivraisonController extends Controller
{
    /**
     * @Route("", name="TravailLivraison_list")
     */
    public function indexAction(Request $request)
    {
        $TravailLivraison = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailLivraison')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('TravailLivraison/index.html.twig',array('list' => $TravailLivraison));
    }

    /**
     * @Route("/voir/{id}", name="TravailLivraison_voir")
     */
    public function voirAction($id,Request $request)
    {
        $TravailLivraison = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailLivraison')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('TravailLivraison/voir.html.twig',array('enregistrement' => $TravailLivraison));
    }

    /**
     * @Route("/create", name="TravailLivraison_create")
     */
    public function createAction(Request $request)
    {
        $TravailLivraison = new TravailLivraison;

        $form = $this->createForm(TravailLivraisonFormType::class,$TravailLivraison);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($TravailLivraison);
            $em->flush();

            $this->addFlash('notice','TravailLivraison Ajouté');

            return $this->redirectToRoute('TravailLivraison_list');
        }

        return $this->render('TravailLivraison/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="TravailLivraison_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailLivraison = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailLivraison')
                        ->find($id);

        return $this->render('TravailLivraison/delete.html.twig',array('id'=> $id,'nom' => $TravailLivraison->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="TravailLivraison_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailLivraison = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailLivraison')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($TravailLivraison);
        $em->flush();

        $this->addFlash('notice','TravailLivraison Effacé');

        return $this->redirectToRoute('TravailLivraison_list');

    }

    /**
     * @Route("/edit/{id}", name="TravailLivraison_edit")
     */
    public function editAction($id,Request $request)
    {
        $TravailLivraison = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailLivraison')
                        ->find($id);

        $form = $this->createForm(TravailLivraisonFormType::class,$TravailLivraison);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $TravailLivraison = $em->getRepository('AppBundle:TravailLivraison')->find($id);
            $em->flush();

            $this->addFlash('notice','TravailLivraison Mis à jour');

            return $this->redirectToRoute('TravailLivraison_list');
        }

        return $this->render('TravailLivraison/edit.html.twig',array('form' => $form->createView(),'nom' => $TravailLivraison->getNom()));
    }

}
