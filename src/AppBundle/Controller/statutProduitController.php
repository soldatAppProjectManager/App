<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\statutProduit;
use AppBundle\Entity\TermesDevisRelation;
use AppBundle\Form\statutProduitFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/statutProduit")
 */
class statutProduitController extends Controller
{
    /**
     * @Route("", name="statutProduit_list")
     */
    public function indexAction(Request $request)
    {
        $statutProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:statutProduit')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('statutProduit/index.html.twig',array('list' => $statutProduit));
    }

    /**
     * @Route("/voir/{id}", name="statutProduit_voir")
     */
    public function voirAction($id,Request $request)
    {
        $statutProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:statutProduit')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('statutProduit/voir.html.twig',array('enregistrement' => $statutProduit));
    }

    /**
     * @Route("/create", name="statutProduit_create")
     */
    public function createAction(Request $request)
    {
        $statutProduit = new statutProduit;

        $form = $this->createForm(statutProduitFormType::class,$statutProduit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($statutProduit);

            $em->flush();



            $this->addFlash('notice','statutProduit Ajouté');

            return $this->redirectToRoute('statutProduit_list');

        }

        return $this->render('statutProduit/create.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/delete/{id}", name="statutProduit_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $statutProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:statutProduit')
                        ->find($id);

        return $this->render('statutProduit/delete.html.twig',array('id'=> $id,'nom' => $statutProduit->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="statutProduit_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $statutProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:statutProduit')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($statutProduit);
        $em->flush();

        $this->addFlash('notice','statutProduit Effacé');

        return $this->redirectToRoute('statutProduit_list');

    }

    /**
     * @Route("/edit/{id}", name="statutProduit_edit")
     */
    public function editAction($id,Request $request)
    {
        $statutProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:statutProduit')
                        ->find($id);

        $form = $this->createForm(statutProduitFormType::class,$statutProduit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $statutProduit = $em->getRepository('AppBundle:statutProduit')->find($id);
            $em->flush();

            $this->addFlash('notice','statutProduit Mis à jour');

            return $this->redirectToRoute('statutProduit_list');
        }

        return $this->render('statutProduit/edit.html.twig',array('form' => $form->createView(),'nom' => $statutProduit->getNom()));
    }

}
