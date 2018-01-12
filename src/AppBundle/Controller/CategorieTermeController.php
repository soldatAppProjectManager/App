<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\CategorieTerme;
use AppBundle\Entity\TermesDevisRelation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\CategorieTermeFormType;
/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/CategorieTerme")
 */
class CategorieTermeController extends Controller
{
    /**
     * @Route("", name="CategorieTerme_list")
     */
    public function indexAction(Request $request)
    {
        $CategorieTerme = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieTerme')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('CategorieTerme/index.html.twig',array('list' => $CategorieTerme));
    }

    /**
     * @Route("/voir/{id}", name="CategorieTerme_voir")
     */
    public function voirAction($id,Request $request)
    {
        $CategorieTerme = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieTerme')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('CategorieTerme/voir.html.twig',array('enregistrement' => $CategorieTerme));
    }

    /**
     * @Route("/create", name="CategorieTerme_create")
     */
    public function createAction(Request $request)
    {
        $CategorieTerme = new CategorieTerme;

        $form = $this->createForm(CategorieTermeFormType::class,$CategorieTerme);  


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($CategorieTerme);
            $em->flush();

            $this->addFlash('notice','Terme Commercial Créé');

            return $this->redirectToRoute('CategorieTerme_list');
        }

        return $this->render('CategorieTerme/create.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Route("/delete/{id}", name="CategorieTerme_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $CategorieTerme = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieTerme')
                        ->find($id);

        return $this->render('CategorieTerme/delete.html.twig',array('id'=> $id,'nom' => $CategorieTerme->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="CategorieTerme_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $CategorieTerme = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieTerme')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($CategorieTerme);
        $em->flush();

        $this->addFlash('notice','Terme Commercial Effacé');

        return $this->redirectToRoute('CategorieTerme_list');

    }

    /**
     * @Route("/edit/{id}", name="CategorieTerme_edit")
     */
    public function editAction($id,Request $request)
    {
        $CategorieTerme = $this->getDoctrine()
                        ->getRepository('AppBundle:CategorieTerme')
                        ->find($id);

        $form = $this->createForm(CategorieTermeFormType::class,$CategorieTerme);  

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $CategorieTerme = $em->getRepository('AppBundle:CategorieTerme')->find($id);
            $em->flush();

            $this->addFlash('notice','Terme Commercial Mis à jour');

            return $this->redirectToRoute('CategorieTerme_list');
        }

        return $this->render('CategorieTerme/edit.html.twig',array('form' => $form->createView(),'nom' => $CategorieTerme->getNom()));
    }

}
