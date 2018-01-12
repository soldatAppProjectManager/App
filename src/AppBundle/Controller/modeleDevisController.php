<?php

namespace AppBundle\Controller;

use AppBundle\Entity\modeleDevis;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\modeleDevisFormType;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("/modeleDevis")
 */
class modeleDevisController extends Controller
{
    /**
     * @Route("", name="modeleDevis_list")
     */
    public function indexAction(Request $request)
    {
        $modeleDevis = $this->getDoctrine()
                        ->getRepository('AppBundle:modeleDevis')
                        ->findAll();

        // replace this example code with whatever you need
        return $this->render('modeleDevis/index.html.twig',array('list' => $modeleDevis));
    }

    /**
     * @Route("/voir/{id}", name="modeleDevis_voir")
     */
    public function voirAction($id,Request $request)
    {
        $modeleDevis = $this->getDoctrine()
                        ->getRepository('AppBundle:modeleDevis')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('modeleDevis/voir.html.twig',array('enregistrement' => $modeleDevis));
    }

    /**
     * @Route("/create", name="modeleDevis_create")
     */
    public function createAction(Request $request)
    {
        $modeleDevis = new modeleDevis;

        $form = $this->createForm(modeleDevisFormType::class,$modeleDevis);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $file= $form['modele']->getData();

            $em = $this->getDoctrine()->getManager();


            // echo $this->getParameter('repertoire_modeledevis')."/".$modeleDevis->getNom(); die();

            $file->move(
                $this->getParameter('repertoire_modeledevis'),
                $modeleDevis->getNom().".html.twig"
                );

            $modeleDevis->setModele($modeleDevis->getNom().".html.twig");

            $em->persist($modeleDevis);
            $em->flush();

            $this->addFlash('notice','modeleDevis Ajouté');

            return $this->redirectToRoute('modeleDevis_list');
        }

        return $this->render('modeleDevis/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="modeleDevis_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $modeleDevis = $this->getDoctrine()
                        ->getRepository('AppBundle:modeleDevis')
                        ->find($id);

        return $this->render('modeleDevis/delete.html.twig',array('id'=> $id,'nom' => $modeleDevis->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="modeleDevis_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $modeleDevis = $this->getDoctrine()
                        ->getRepository('AppBundle:modeleDevis')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($modeleDevis);
        $em->flush();

        $this->addFlash('notice','modeleDevis Effacé');

        return $this->redirectToRoute('modeleDevis_list');

    }

    /**
     * @Route("/edit/{id}", name="modeleDevis_edit")
     */
    public function editAction($id,Request $request)
    {
        $modeleDevis = $this->getDoctrine()
                        ->getRepository('AppBundle:modeleDevis')
                        ->find($id);

        $form = $this->createForm(modeleDevisFormType::class,$modeleDevis);                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $metier = $em->getRepository('AppBundle:modeleDevis')->find($id);
            $em->flush();

            $this->addFlash('notice','modeleDevis Mis à jour');

            return $this->redirectToRoute('modeleDevis_list');
        }

        return $this->render('modeleDevis/edit.html.twig',array('form' => $form->createView(),'nom' => $modeleDevis->getNom()));
    }

}
