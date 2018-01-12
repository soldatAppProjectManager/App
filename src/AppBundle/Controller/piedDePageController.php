<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Metier;
use AppBundle\Entity\piedDePage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\piedDePageFormType;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/piedDePage")
 */
class piedDePageController extends Controller
{
    /**
     * @Route("", name="piedDePage_list")
     */
    public function indexAction(Request $request)
    {
        $piedDePage = $this->getDoctrine()
                        ->getRepository('AppBundle:piedDePage')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('piedDePage/index.html.twig',array('list' => $piedDePage));
    }

    /**
     * @Route("/voir/{id}", name="piedDePage_voir")
     */
    public function voirAction($id,Request $request)
    {
        $piedDePage = $this->getDoctrine()
                        ->getRepository('AppBundle:piedDePage')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('piedDePage/voir.html.twig',array('enregistrement' => $piedDePage));
    }

    /**
     * @Route("/create", name="piedDePage_create")
     */
    public function createAction(Request $request)
    {
        $piedDePage = new piedDePage;

        $form = $this->createForm(piedDePageFormType::class,$piedDePage);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($piedDePage);
            $em->flush();

            $this->addFlash('notice','piedDePage Ajoutée');

            return $this->redirectToRoute('piedDePage_list');
        }

        return $this->render('piedDePage/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="piedDePage_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $piedDePage = $this->getDoctrine()
                        ->getRepository('AppBundle:piedDePage')
                        ->find($id);

        return $this->render('metier/delete.html.twig',array('id'=> $id,'nom' => $piedDePage->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="piedDePage_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $piedDePage = $this->getDoctrine()
                        ->getRepository('AppBundle:piedDePage')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($piedDePage);
        $em->flush();

        $this->addFlash('notice','piedDePage Effacée');

        return $this->redirectToRoute('piedDePage_list');

    }

    /**
     * @Route("/edit/{id}", name="piedDePage_edit")
     */
    public function editAction($id,Request $request)
    {
        $piedDePage = $this->getDoctrine()
                        ->getRepository('AppBundle:piedDePage')
                        ->find($id);

        $form = $this->createForm(piedDePageFormType::class,$piedDePage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $piedDePage = $em->getRepository('AppBundle:piedDePage')->find($id);
            $em->flush();

            $this->addFlash('notice','piedDePageMis à jour');

            return $this->redirectToRoute('piedDePage_list');
        }

        return $this->render('piedDePage/edit.html.twig',array('form' => $form->createView(),'titre' => $piedDePage->getTitre()));
    }

}
