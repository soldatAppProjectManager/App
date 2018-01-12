<?php

namespace AppBundle\Controller;

use DateTime;
use AppBundle\Entity\TermeCommercial;
use AppBundle\Entity\TermesDevisRelation;
use AppBundle\Form\TermeCommercialFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use \Symfony\Bridge\Doctrine\Form\Type\EntityType;;
/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/TermeCommercial")
 */
class TCController extends Controller
{
    /**
     * @Route("", name="TermeCommercial_list")
     */
    public function indexAction(Request $request)
    {
        $TermeCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TermeCommercial')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('TermeCommercial/index.html.twig',array('list' => $TermeCommercial));
    }

    /**
     * @Route("/voir/{id}", name="TermeCommercial_voir")
     */
    public function voirAction($id,Request $request)
    {
        $TermeCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TermeCommercial')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('TermeCommercial/voir.html.twig',array('enregistrement' => $TermeCommercial));
    }

    /**
     * @Route("/create", name="TermeCommercial_create")
     */
    public function createAction(Request $request)
    {
        $TermeCommercial = new TermeCommercial;

        $form = $this->createForm(TermeCommercialFormType::class,$TermeCommercial);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($TermeCommercial);
            $em->flush();

            $this->addFlash('notice','Terme Commercial Créé');

            return $this->redirectToRoute('TermeCommercial_list');
        }

        return $this->render('TermeCommercial/create.html.twig', array(
            'form' => $form->createView()));
    }

    /**
     * @Route("/delete/{id}", name="TermeCommercial_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TermeCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TermeCommercial')
                        ->find($id);

        return $this->render('TermeCommercial/delete.html.twig',array('id'=> $id,'nom' => $TermeCommercial->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="TermeCommercial_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $TermeCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TermeCommercial')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($TermeCommercial);
        $em->flush();

        $this->addFlash('notice','Terme Commercial Effacé');

        return $this->redirectToRoute('TermeCommercial_list');

    }

    /**
     * @Route("/edit/{id}", name="TermeCommercial_edit")
     */
    public function editAction($id,Request $request)
    {
        $TermeCommercial = $this->getDoctrine()
                        ->getRepository('AppBundle:TermeCommercial')
                        ->find($id);

        $form = $this->createForm(TermeCommercialFormType::class,$TermeCommercial);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $TermeCommercial = $em->getRepository('AppBundle:TermeCommercial')->find($id);
            $em->flush();

            $this->addFlash('notice','Terme Commercial Mis à jour');

            return $this->redirectToRoute('TermeCommercial_list');
        }

        return $this->render('TermeCommercial/edit.html.twig',array('form' => $form->createView(),'nom' => $TermeCommercial->getNom()));
    }

}
