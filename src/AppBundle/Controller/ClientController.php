<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Monnaie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\Query;
use AppBundle\Form\ClientFormType;
use AppBundle\Form\ClientDevisFormType;

/**
 * @Security("has_role('ROLE_COMMERCIAL') or has_role('ROLE_ADMIN')")
 * @Route("/client")
 */
class ClientController extends Controller
{
    /**
     * @Route("", name="client_list")
     */
    public function indexAction(Request $request)
    {
        $clients = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->findAll();
                        

        // replace this example code with whatever you need
        return $this->render('client/index.html.twig',array('clients' => $clients));
    }

    /**
     * @Route("/voir/{id}", name="client_voir")
     */
    public function voirAction($id,Request $request)
    {
        $client = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('client/voir.html.twig',array('client' => $client));
    }

    /**
     * @Route("/create/{source}", name="client_create", defaults={"source": "Client"})
     */
    public function createAction($source,Request $request)
    {
        $client = new Client;

        $client->setCommercial($this->getUser());

        if ($source == "devis") {
            $form = $this->createForm(ClientDevisFormType::class,$client);
            $client->setCommercial($this->getUser());
        }
        else $form = $this->createForm(ClientFormType::class,$client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($client);
            $em->flush();

            $this->addFlash('notice','Client Ajouté');

            return $this->redirectToRoute('contact_create',array('source' => $source, 'organisation' => "Client", 'id' => $client->getId()));
        }

        return $this->render('client/createplus.html.twig', array(
            'form' => $form->createView(),
            'source' => $source
        ));
    }

    /**
     * @Route("/delete/{id}", name="client_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $client = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        return $this->render('client/delete.html.twig',array('id'=> $id,'nom' => $client->getNom()));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="client_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $client = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($client);
        $em->flush();

        $this->addFlash('notice','Client Effacé');

        return $this->redirectToRoute('client_list');
    }

    /**
     * @Route("/edit/{id}", name="client_edit")
     */
    public function editAction($id,Request $request)
    {
        $client = $this->getDoctrine()
                        ->getRepository('AppBundle:Client')
                        ->find($id);

        if ($client->getPrive()){
            $selected = true;
        } else {
            $selected = false;            
        }

        $form = $this->createForm(ClientFormType::class,$client);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository('AppBundle:Client')->find($id);
            $em->flush();

            $this->addFlash('notice','Client Mis à jour');

            return $this->redirectToRoute('client_list');
        }

        return $this->render('client/edit.html.twig',array('form' => $form->createView(),'nom' => $client->getNom()));
    }


    /**
     * @Route("/contact/default", name="client_contact_defaut")
     * @Method("POST")  
     */
    public function contactParDefautAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $contactid = $request->request->get('contactid');
            $clientid = $request->request->get('clientid');
            $em = $this->getDoctrine()->getManager();

            $contact = $em->getRepository('AppBundle:contact')->find($contactid);
            $client = $em->getRepository('AppBundle:Client')->find($clientid);

            $client->setContactParDefaut($contact);

            $em->flush();
            
            return new JsonResponse(array(
                'code'                  => 1,
            ));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     *
     * @Route("/contacts", name="get_contacts")
     * @Method("POST")
     */
    public function getContactsAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $Client = $this->getDoctrine()->getRepository('AppBundle:Client')->find($request->request->get('id'));
            
            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery(
                        'SELECT contacts
                         FROM AppBundle:contact contacts
                         WHERE contacts.client = :client
                         ORDER BY contacts.nom ASC'
                         )->setParameter('client', $Client->getId());

            $contacts = $query->getResult(Query::HYDRATE_ARRAY);

            return new JsonResponse(array(
                'code'                  => 1,
                'contacts'               => $contacts
            ));

           return $this->redirectToRoute('homepage');


        } else {
            return $this->redirectToRoute('homepage');
        }
    }

}
