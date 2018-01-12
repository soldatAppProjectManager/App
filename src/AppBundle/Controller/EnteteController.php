<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Metier;
use AppBundle\Entity\Entete;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\EnteteFormType;
/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/Entete")
 */
class EnteteController extends Controller
{
    /**
     * @Route("", name="Entete_list")
     */
    public function indexAction(Request $request)
    {
        $Entete = $this->getDoctrine()
                        ->getRepository('AppBundle:Entete')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('Entete/index.html.twig',array('list' => $Entete));
    }

    /**
     * @Route("/voir/{id}", name="Entete_voir")
     */
    public function voirAction($id,Request $request)
    {
        $Entete = $this->getDoctrine()
                        ->getRepository('AppBundle:Entete')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('Entete/voir.html.twig',array('enregistrement' => $Entete));
    }

    /**
     * @Route("/create", name="Entete_create")
     */
    public function createAction(Request $request)
    {
        $Entete = new Entete;

        $form = $this->createForm(EnteteFormType::class,$Entete);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($Entete);
            $em->flush();

            $this->addFlash('notice','Entete Ajoutée');

            return $this->redirectToRoute('Entete_list');
        }

        return $this->render('Entete/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="Entete_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $Entete = $this->getDoctrine()
                        ->getRepository('AppBundle:Entete')
                        ->find($id);

        return $this->render('Entete/delete.html.twig',array('id'=> $id,'nom' => $Entete->getTitre()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="Entete_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $Entete = $this->getDoctrine()
                        ->getRepository('AppBundle:Entete')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($Entete);
        $em->flush();

        $this->addFlash('notice','Entete Effacée');

        return $this->redirectToRoute('Entete_list');

    }

    /**
     * @Route("/edit/{id}", name="Entete_edit")
     */
    public function editAction($id,Request $request)
    {
        $Entete = $this->getDoctrine()
                        ->getRepository('AppBundle:Entete')
                        ->find($id);


        $form = $this->createForm(EnteteFormType::class,$Entete);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $Entete = $em->getRepository('AppBundle:Entete')->find($id);
            $em->flush();

            $this->addFlash('notice','EnteteMis à jour');

            return $this->redirectToRoute('Entete_list');
        }

        return $this->render('Entete/edit.html.twig',array('form' => $form->createView(),'Entete' => $Entete));
    }

}
