<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Metier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\MetierFormType;
/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/metier")
 */
class MetierController extends Controller
{
    /**
     * @Route("", name="metier_list")
     */
    public function indexAction(Request $request)
    {
        $metier = $this->getDoctrine()
                        ->getRepository('AppBundle:Metier')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('metier/index.html.twig',array('list' => $metier));
    }

    /**
     * @Route("/voir/{id}", name="metier_voir")
     */
    public function voirAction($id,Request $request)
    {
        $metier = $this->getDoctrine()
                        ->getRepository('AppBundle:Metier')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('metier/voir.html.twig',array('enregistrement' => $metier));
    }

    /**
     * @Route("/create", name="metier_create")
     */
    public function createAction(Request $request)
    {
        $metier = new Metier;

        $form = $this->createForm(MetierFormType::class,$metier);                


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($metier);
            $em->flush();

            $this->addFlash('notice','Metier Ajouté');

            return $this->redirectToRoute('metier_list');
        }

        return $this->render('metier/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="metier_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $metier = $this->getDoctrine()
                        ->getRepository('AppBundle:Metier')
                        ->find($id);

        return $this->render('metier/delete.html.twig',array('id'=> $id,'nom' => $metier->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="metier_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $metier = $this->getDoctrine()
                        ->getRepository('AppBundle:Metier')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($metier);
        $em->flush();

        $this->addFlash('notice','Metier Effacé');

        return $this->redirectToRoute('metier_list');

    }

    /**
     * @Route("/edit/{id}", name="metier_edit")
     */
    public function editAction($id,Request $request)
    {
        $metier = $this->getDoctrine()
                        ->getRepository('AppBundle:Metier')
                        ->find($id);

        $form = $this->createForm(MetierFormType::class,$metier);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $metier = $em->getRepository('AppBundle:Metier')->find($id);
            $em->flush();

            $this->addFlash('notice','Metier Mis à jour');

            return $this->redirectToRoute('metier_list');
        }

        return $this->render('metier/edit.html.twig',array('form' => $form->createView(),'nom' => $metier->getNom()));
    }

}
