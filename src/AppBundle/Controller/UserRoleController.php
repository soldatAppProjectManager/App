<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Userrole controller.
 * @Security("has_role('ROLE_ADMIN')")
 * @Route("userrole")
 */
class UserRoleController extends Controller
{
    /**
     * Lists all userRole entities.
     *
     * @Route("/", name="userrole_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userRoles = $em->getRepository('AppBundle:UserRole')->findAll();

        return $this->render('userrole/index.html.twig', array(
            'userRoles' => $userRoles,
        ));
    }

    /**
     * Creates a new userRole entity.
     *
     * @Route("/new", name="userrole_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userRole = new Userrole();
        $form = $this->createFormBuilder($userRole)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('role', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userRole);
            $em->flush();

            return $this->redirectToRoute('userrole_show', array('id' => $userRole->getId()));
        }

        return $this->render('userrole/new.html.twig', array(
            'userRole' => $userRole,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a userRole entity.
     *
     * @Route("/{id}", name="userrole_show")
     * @Method("GET")
     */
    public function showAction($id,Request $request)
    {
        $userRole = $this->getDoctrine()
                        ->getRepository('AppBundle:UserRole')
                        ->find($id);

        return $this->render('userrole/show.html.twig', array(
            'userRole' => $userRole,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing userRole entity.
     *
     * @Route("/{id}/edit", name="userrole_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id,Request $request)
    {

        $userRole = $this->getDoctrine()
                        ->getRepository('AppBundle:UserRole')
                        ->find($id);
       
        $form = $this->createFormBuilder($userRole)
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('role', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('userrole_index');
        }

        return $this->render('userrole/edit.html.twig', array(
            'userRole' => $userRole,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a userRole entity.
     *
     * @Route("/delete/{id}", name="userrole_delete")
     * 
     */
    public function deleteAction($id,Request $request)
    {
        $userRole = $this->getDoctrine()
                        ->getRepository('AppBundle:UserRole')
                        ->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($userRole);
        $em->flush();

        return $this->redirectToRoute('userrole_index');
    }

}
