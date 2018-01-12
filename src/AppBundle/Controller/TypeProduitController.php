<?php

namespace AppBundle\Controller;

use AppBundle\Entity\monnaie;
use AppBundle\Entity\TypeProduit;
use AppBundle\Form\TypeProduitFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/typeproduit")
 */
class TypeProduitController extends Controller
{
    /**
     * @Route("", name="typeproduit_list")
     */
    public function indexAction(Request $request)
    {
        $TypeProduit = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeProduit')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('typeproduit/index.html.twig',array('list' => $TypeProduit));
    }

    /**
     * @Route("/voir/{id}", name="typeproduit_voir")
     */
    public function voirAction($id,Request $request)
    {
        $monnaie = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeProduit')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('typeproduit/voir.html.twig',array('enregistrement' => $monnaie));
    }

    /**
     * @Route("/create", name="typeproduit_create")
     */
    public function createAction(Request $request)
    {
        $typeproduit = new TypeProduit;

        $form = $this->createForm(TypeProduitFormType::class,$typeproduit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($typeproduit);
            $em->flush();

            $this->addFlash('notice','Type Produit Ajouté');

            return $this->redirectToRoute('typeproduit_list');
        }

        return $this->render('typeproduit/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="typeproduit_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $typeproduit = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeProduit')
                        ->find($id);

        return $this->render('typeproduit/delete.html.twig',array('id'=> $id,'nom' => $typeproduit->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="typeproduit_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $typeproduit = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeProduit')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($typeproduit);
        $em->flush();

        $this->addFlash('notice','Type Produit Effacé');

        return $this->redirectToRoute('typeproduit_list');

    }

    /**
     * @Route("/edit/{id}", name="typeproduit_edit")
     */
    public function editAction($id,Request $request)
    {
        $typeproduit = $this->getDoctrine()
                        ->getRepository('AppBundle:TypeProduit')
                        ->find($id);

        $form = $this->createForm(TypeProduitFormType::class,$typeproduit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $typeproduit = $em->getRepository('AppBundle:TypeProduit')->find($id);
            $em->flush();

            $this->addFlash('notice','Type Produit Mis à jour');

            return $this->redirectToRoute('typeproduit_list');
        }

        return $this->render('typeproduit/edit.html.twig',array('form' => $form->createView(),'nom' => $typeproduit->getNom()));
    }

}
