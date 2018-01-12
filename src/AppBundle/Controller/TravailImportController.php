<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TravailImport;
use AppBundle\Form\TravailImportFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/TravailImport")
 */
class TravailImportController extends Controller
{
    /**
     * @Route("", name="TravailImport_list")
     */
    public function indexAction(Request $request)
    {
        $TravailImport = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailImport')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('TravailImport/index.html.twig',array('list' => $TravailImport));
    }

    /**
     * @Route("/voir/{id}", name="TravailImport_voir")
     */
    public function voirAction($id,Request $request)
    {
        $TravailImport = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailImport')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('TravailImport/voir.html.twig',array('enregistrement' => $TravailImport));
    }

    /**
     * @Route("/create", name="TravailImport_create")
     */
    public function createAction(Request $request)
    {
        $TravailImport = new TravailImport;

        $form = $this->createForm(TravailImportFormType::class,$TravailImport);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($TravailImport);
            $em->flush();

            $this->addFlash('notice','TravailImport Ajouté');

            return $this->redirectToRoute('TravailImport_list');
        }

        return $this->render('TravailImport/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="TravailImport_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailImport = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailImport')
                        ->find($id);

        return $this->render('TravailImport/delete.html.twig',array('id'=> $id,'nom' => $TravailImport->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="TravailImport_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TravailImport = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailImport')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($TravailImport);
        $em->flush();

        $this->addFlash('notice','TravailImport Effacé');

        return $this->redirectToRoute('TravailImport_list');

    }

    /**
     * @Route("/edit/{id}", name="TravailImport_edit")
     */
    public function editAction($id,Request $request)
    {
        $TravailImport = $this->getDoctrine()
                        ->getRepository('AppBundle:TravailImport')
                        ->find($id);

        $form = $this->createForm(TravailImportFormType::class,$TravailImport);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $TravailImport = $em->getRepository('AppBundle:TravailImport')->find($id);
            $em->flush();

            $this->addFlash('notice','TravailImport Mis à jour');

            return $this->redirectToRoute('TravailImport_list');
        }

        return $this->render('TravailImport/edit.html.twig',array('form' => $form->createView(),'nom' => $TravailImport->getNom()));
    }

}
