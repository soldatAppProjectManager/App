<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TravailAvantVente;
use AppBundle\Form\TravailAvantVenteFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/TravailAvantVente")
 */
class TravailAvantVenteController extends Controller
{
    /**
     * @Route("", name="TravailAvantVente_list")
     */
    public function indexAction(Request $request)
    {
        $TravailAvantVente = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailAvantVente')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('TravailAvantVente/index.html.twig',array('list' => $TravailAvantVente));
    }

    /**
     * @Route("/voir/{id}", name="TravailAvantVente_voir")
     */
    public function voirAction($id,Request $request)
    {
        $TravailAvantVente = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailAvantVente')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('TravailAvantVente/voir.html.twig',array('enregistrement' => $TravailAvantVente));
    }

    /**
     * @Route("/create", name="TravailAvantVente_create")
     */
    public function createAction(Request $request)
    {
        $TravailAvantVente = new TravailAvantVente;

        $form = $this->createForm(TravailAvantVenteFormType::class,$TravailAvantVente);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($TravailAvantVente);
            $em->flush();

            $this->addFlash('notice','TravailAvantVente Ajouté');

            return $this->redirectToRoute('TravailAvantVente_list');
        }

        return $this->render('TravailAvantVente/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="TravailAvantVente_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailAvantVente = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailAvantVente')
                        ->find($id);

        return $this->render('TravailAvantVente/delete.html.twig',array('id'=> $id,'nom' => $TravailAvantVente->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="TravailAvantVente_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailAvantVente = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailAvantVente')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($TravailAvantVente);
        $em->flush();

        $this->addFlash('notice','TravailAvantVente Effacé');

        return $this->redirectToRoute('TravailAvantVente_list');

    }

    /**
     * @Route("/edit/{id}", name="TravailAvantVente_edit")
     */
    public function editAction($id,Request $request)
    {
        $TravailAvantVente = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailAvantVente')
                        ->find($id);

        $form = $this->createForm(TravailAvantVenteFormType::class,$TravailAvantVente);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $TravailAvantVente = $em->getRepository('AppBundle:TravailAvantVente')->find($id);
            $em->flush();

            $this->addFlash('notice','TravailAvantVente Mis à jour');

            return $this->redirectToRoute('TravailAvantVente_list');
        }

        return $this->render('TravailAvantVente/edit.html.twig',array('form' => $form->createView(),'nom' => $TravailAvantVente->getNom()));
    }

}
