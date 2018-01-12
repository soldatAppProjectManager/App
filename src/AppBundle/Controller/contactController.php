<?php

namespace AppBundle\Controller;

use AppBundle\Entity\contact;
use AppBundle\Entity\Monnaie;

use AppBundle\Form\ContactFormType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/contact")
 */
class contactController extends Controller
{

    /**
     * @Route("/voir/{source}/{id}", name="contact_voir")
     */
    public function voirAction($id,$source,Request $request)
    {
        $contact = $this->getDoctrine()
                        ->getRepository('AppBundle:contact')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('contact/voir.html.twig',array('contact' => $contact,'source' => $source));
    }

    /**
     * SOURCE : client
     * @Route("/create/{source}/{organisation}/{id}", name="contact_create", defaults={"source": "Client", "organisation": "Client"})
     */
    public function createAction($id,$source,$organisation,Request $request)
    {
        $contact = new contact;

        $form = $this->createForm(ContactFormType::class,$contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            if ($organisation == 'Client') {
                $organisation=$this->getDoctrine()->getRepository('AppBundle:Client')->find($id);
                $contact->setClient($organisation);
            }
            else {
                $organisation=$this->getDoctrine()->getRepository('AppBundle:Fournisseur')->find($id);
                $contact->setFournisseur($organisation);
            }

            $em->persist($contact);
            $em->flush();

            $this->addFlash('notice','contact Ajouté');

            /* go back to source */


            if ($source=='devis') return $this->redirectToRoute('devis_create');
            elseif ($source=='Fournisseur') return $this->redirectToRoute('fournisseur_voir',array('id' => $organisation->getId()));
            else return $this->redirectToRoute('client_voir',array('id' => $organisation->getId()));
        }
        return $this->render('contact/create.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'source' => $source));
    }

    /**
     * @Route("/delete/{source}/{id}", name="contact_delete")
     */
    public function deleteAction($id,$source,Request $request)
    {
        // replace this example code with whatever you need
       $contact = $this->getDoctrine()
                        ->getRepository('AppBundle:contact')
                        ->find($id);

        return $this->render('contact/delete.html.twig',array('contact' => $contact,'source' => $source));
    }

    /**
     * @Route("/deleteConfirmed/{source}/{id}", name="contact_delete_confirmed")
     */
    public function deleteConfirmedAction($id,$source,Request $request)
    {
        // replace this example code with whatever you need
       $contact = $this->getDoctrine()
                        ->getRepository('AppBundle:contact')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($contact);
        $em->flush();

        $this->addFlash('notice','contact Effacé');

            if ($source=='Fournisseur') return $this->redirectToRoute('fournisseur_voir',array('id' =>  $contact->getFournisseur()->getId()));
            else return $this->redirectToRoute('client_voir',array('id' => $contact->getClient()->getId()));
    }

    /**
     * @Route("/edit/{source}/{id}", name="contact_edit")
     */
    public function editAction($source,$id,Request $request)
    {
        $contact = $this->getDoctrine()
                        ->getRepository('AppBundle:contact')
                        ->find($id);

        $form = $this->createForm(ContactFormType::class,$contact);        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $contact = $em->getRepository('AppBundle:contact')->find($id);
            $em->flush();

            $this->addFlash('notice','contact Mis à jour');

            if ($source=='Fournisseur') return $this->redirectToRoute('fournisseur_voir',array('id' =>  $contact->getFournisseur()->getId()));
            else return $this->redirectToRoute('client_voir',array('id' => $contact->getClient()->getId()));
        }
        if ($source=='Fournisseur') return $this->render('contact/edit.html.twig',array('form' => $form->createView(),'source' => $source,'contact' => $contact));
        else return $this->render('contact/edit.html.twig',array('form' => $form->createView(),'source' => $source,'contact' => $contact));
    }

}



