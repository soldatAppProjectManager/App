<?php

namespace AppBundle\Controller;
use Curl\Curl;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\Monnaie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use AppBundle\Form\FournisseurFormType;


/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/fournisseur")
 */
class FournisseurController extends Controller
{
    /**
     * @Route("", name="fournisseur_list")
     */
    public function indexAction(Request $request)
    {
        $fournisseurs = $this->getDoctrine()
                        ->getRepository('AppBundle:Fournisseur')
                        ->findAllByAscNom()->getQuery()->getResult();

        // replace this example code with whatever you need
        return $this->render('fournisseur/index.html.twig',array('fournisseurs' => $fournisseurs));
    }

    /**
     * @Route("/voir/{id}", name="fournisseur_voir")
     */
    public function voirAction($id,Request $request)
    {
        $fournisseur = $this->getDoctrine()
                        ->getRepository('AppBundle:Fournisseur')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('fournisseur/voir.html.twig',array('fournisseur' => $fournisseur));
    }

    /**
     * @Route("/create/{source}", name="fournisseur_create", defaults={"source": "Fournisseur"})
     */
    public function createAction($source,Request $request)
    {
        $fournisseur = new Fournisseur;

        $form = $this->createForm(FournisseurFormType::class,$fournisseur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($fournisseur);
            $em->flush();

            $this->addFlash('notice','Founisseur Ajoutée');

            return $this->redirectToRoute('contact_create',array('source' => $source, 'organisation' => "Fournisseur", 'id' => $fournisseur->getId()));
        }

        return $this->render('fournisseur/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="fournisseur_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $fournisseur = $this->getDoctrine()
                        ->getRepository('AppBundle:Fournisseur')
                        ->find($id);

        return $this->render('fournisseur/delete.html.twig',array('id'=> $id,'nom' => $fournisseur->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="fournisseur_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $fournisseur = $this->getDoctrine()
                        ->getRepository('AppBundle:Fournisseur')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($fournisseur);
        $em->flush();

        $this->addFlash('notice','Fournisseur Effacé');

        return $this->redirectToRoute('fournisseur_list');

    }

    /**
     * @Route("/edit/{id}", name="fournisseur_edit")
     */
    public function editAction($id,Request $request)
    {
        $fournisseur = $this->getDoctrine()
                        ->getRepository('AppBundle:Fournisseur')
                        ->find($id);

        $form = $this->createForm(FournisseurFormType::class,$fournisseur);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();
            $fournisseur = $em->getRepository('AppBundle:Fournisseur')->find($id);
            $em->flush();

            $this->addFlash('notice','Fournisseur Mis à jour');

            return $this->redirectToRoute('fournisseur_list');
        }

        return $this->render('fournisseur/edit.html.twig',array('form' => $form->createView(),'nom' => $fournisseur->getNom()));
    }

    /**
     *
     * @Route("/fa_devise_terme_by_fournisseur", name="fournisseur_data")
     * @Method("POST")
     */
    public function DataByFournisseur(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->request->get('id');
            $em = $this->getDoctrine()->getManager();

            $fournisseur = $em->getRepository('AppBundle:Fournisseur')->find($id);

            $encoders = array(new JsonEncoder());

            $normalizer = new ObjectNormalizer();

            $normalizers = array($normalizer);

            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getNom();
            });

            $serializer = new Serializer($normalizers, $encoders);

            $fournisseurNormalised=$normalizer->normalize($fournisseur);
            
            return new JsonResponse(array(
                'code'                  => 1,
                'fournisseur'           => $fournisseurNormalised,
                'taux'                  => $this->getDoctrine()->getRepository('AppBundle:Parametres')->find(2)->getValeur()
                // 'coefficient'           => 
            ));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }



    /**
     * @Route("/contact/default", name="fournisseur_contact_defaut")
     * @Method("POST")  
     */
    public function contactParDefautAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $contactid = $request->request->get('contactid');
            $fournisseurid = $request->request->get('fournisseurid');
            $em = $this->getDoctrine()->getManager();

            $contact = $em->getRepository('AppBundle:contact')->find($contactid);
            $fournisseur = $em->getRepository('AppBundle:Fournisseur')->find($fournisseurid);

            $fournisseur->setContactParDefaut($contact);

            $em->flush();
            
            return new JsonResponse(array(
                'code'                  => 1,
            ));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }    

}
