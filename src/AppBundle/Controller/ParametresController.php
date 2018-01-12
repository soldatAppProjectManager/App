<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Parametres;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\ParametresFormType;
/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/Parametres")
 */
class ParametresController extends Controller
{
    /**
     * @Route("", name="Parametres_list")
     */
    public function indexAction(Request $request)
    {
        $Parametres = $this->getDoctrine()
                        ->getRepository('AppBundle:Parametres')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('Parametres/index.html.twig',array('list' => $Parametres));
    }

    /**
     * @Route("/voir/{id}", name="Parametres_voir")
     */
    public function voirAction($id,Request $request)
    {
        $Parametres = $this->getDoctrine()
                        ->getRepository('AppBundle:Parametres')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('Parametres/voir.html.twig',array('enregistrement' => $Parametres));
    }

    /**
     * @Route("/create", name="Parametres_create")
     */
    public function createAction(Request $request)
    {
        $Parametres = new Parametres;

        $form = $this->createForm(ParametresFormType::class,$Parametres);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($Parametres);
            $em->flush();

            $this->addFlash('notice','Parametres Ajouté');

            return $this->redirectToRoute('Parametres_list');
        }

        return $this->render('Parametres/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="Parametres_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $Parametres = $this->getDoctrine()
                        ->getRepository('AppBundle:Parametres')
                        ->find($id);

        return $this->render('Parametres/delete.html.twig',array('id'=> $id,'nom' => $Parametres->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="Parametres_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $Parametres = $this->getDoctrine()
                        ->getRepository('AppBundle:Parametres')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($Parametres);
        $em->flush();

        $this->addFlash('notice','Parametres Effacé');

        return $this->redirectToRoute('Parametres_list');

    }

    /**
     * @Route("/edit/{id}", name="Parametres_edit")
     */
    public function editAction($id,Request $request)
    {
        $Parametres = $this->getDoctrine()
                        ->getRepository('AppBundle:Parametres')
                        ->find($id);

        $form = $this->createForm(ParametresFormType::class,$Parametres);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $metier = $em->getRepository('AppBundle:Parametres')->find($id);
            $em->flush();

            $this->addFlash('notice','Parametres Mis à jour');

            return $this->redirectToRoute('Parametres_list');
        }

        return $this->render('Parametres/edit.html.twig',array('form' => $form->createView(),'nom' => $Parametres->getNom()));
    }

}
