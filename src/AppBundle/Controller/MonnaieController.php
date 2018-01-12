<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Monnaie;
use AppBundle\Form\MonnaieFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Curl\Curl;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/monnaie")
 */
class MonnaieController extends Controller
{
    /**
     * @Route("", name="monnaie_list")
     */
    public function indexAction(Request $request)
    {
        $monnaies = $this->getDoctrine()
                        ->getRepository('AppBundle:Monnaie')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('monnaie/index.html.twig',array('monnaies' => $monnaies));
    }

    /**
     * @Route("/voir/{id}", name="monnaie_voir")
     */
    public function voirAction($id,Request $request)
    {
        $monnaie = $this->getDoctrine()
                        ->getRepository('AppBundle:Monnaie')
                        ->find($id);

        $this->miseAJourDevise($id);               

        // replace this example code with whatever you need
        return $this->render('monnaie/voir.html.twig',array('monnaie' => $monnaie));
    }

    /**
     * @Route("/create", name="monnaie_create")
     */
    public function createAction(Request $request)
    {
        $devise = new Monnaie;

        $form = $this->createForm(MonnaieFormType::class,$devise);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($devise);
            $em->flush();

            $this->addFlash('notice','Monnaie Ajoutée');

            return $this->redirectToRoute('monnaie_list');
        }

        return $this->render('monnaie/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="monnaie_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $monnaie = $this->getDoctrine()
                        ->getRepository('AppBundle:Monnaie')
                        ->find($id);

        return $this->render('monnaie/delete.html.twig',array('id'=> $id,'nom' => $monnaie->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="monnaie_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $monnaie = $this->getDoctrine()
                        ->getRepository('AppBundle:Monnaie')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($monnaie);
        $em->flush();

        $this->addFlash('notice','Monnaie Effacée');

        return $this->redirectToRoute('monnaie_list');

    }

    /**
     * @Route("/edit/{id}", name="monnaie_edit")
     */
    public function editAction($id,Request $request)
    {
        $devise = $this->getDoctrine()
                        ->getRepository('AppBundle:Monnaie')
                        ->find($id);

        $form = $this->createForm(MonnaieFormType::class,$devise);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $monnaie = $em->getRepository('AppBundle:Monnaie')->find($id);
            $em->flush();

            $this->addFlash('notice','Monnaie Mis à jour');

            return $this->redirectToRoute('monnaie_list');
        }

        return $this->render('monnaie/edit.html.twig',array('form' => $form->createView(),'nom' => $devise->getNom()));
    }

    public function miseAJourDevise($id)
    {
        $monnaie = $this->getDoctrine()
                    ->getRepository('AppBundle:Monnaie')
                    ->find($id);

        $monnaie->recupererTauxBkam();

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('notice','Monnaie Mise à jour');        
        return 0;
    }


}
