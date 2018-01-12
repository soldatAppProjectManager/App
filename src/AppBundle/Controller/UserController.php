<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\UserRole;
use AppBundle\Entity\Monnaie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;    

/**
 * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_USER')")
 * @Route("/User")
 */
class UserController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("", name="User_list")
     */
    public function indexAction(Request $request)
    {
        $Users = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->findAll();
                        
        // replace this example code with whatever you need
        return $this->render('User/index.html.twig',array('Users' => $Users));
    }

    /**
     * @Route("/voir/{id}", name="User_voir")
     */
    public function voirAction($id,Request $request)
    {
        $User = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->find($id);

        // replace this example code with whatever you need
        return $this->render('User/voir.html.twig',array('User' => $User));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/create", name="User_create")
     */
    public function createAction(Request $request)
    {
        $User = new User();


        $form = $this->createFormBuilder($User)
            ->add('civilite', ChoiceType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Choisir une civilité'),
                'choices' => array( 'M.' => "M.",
                                    'Mme' => "Mme",
                                    'Melle' => "Melle"),
                'label' => 'Civilité'))
            ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre prénom'), 'label' => 'Prénom'))
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre nom'), 'label' => 'Nom'))
            ->add('genre', ChoiceType::class, array('choices' => array('Femme' => true,'Homme' => false),'multiple'=>false,'expanded'=>true, 'label' => 'Genre'))
            ->add('poste', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre poste'), 'label' => 'Poste'))
            ->add('departement', ChoiceType::class, array('attr' => array('class' => 'selectpicker', 'data-live-search'=> 'true'), 
                'choices' => array( 'Commercial' => "Commercial",
                                    'Technique' => "Technique",
                                    'Recouvrement' => "Recouvrement",
                                    'Facturation' => "Facturation",
                                    'Logistique' => "Logistique",
                                    'Avant-Vente' => "Avant-Vente",
                                    'Comptable' => "Comptable"),
                'label' => 'Département'))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre email'), 'label' => 'Email'))
            ->add('telephone', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre numéro de tel'), 'label' => 'Téléphone'))            
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')))        
            ->getForm();



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($User);
            $em->flush();

            $this->addFlash('notice','User Ajouté');

            return $this->redirectToRoute('User_createuserinfo',array('id' => $User->getId()));
        }

        return $this->render('User/create.html.twig', array(
            'form' => $form->createView()));
    }


    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/createuserinfo/{id}", name="User_createuserinfo")
     */
    public function createuserinfoAction($id,Request $request)
    {
        $User = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->find($id);

        $roles = $this->getDoctrine()
                        ->getRepository('AppBundle:UserRole')
                        ->findAll(); 

        $rolesChoices = [];
        $rolesChoice_attr = [];
        $grantedRoles = $User->getRoles();

        foreach ($roles as $role){
            $rolesChoices[$role->getNom()] = $role->getId();
            if (in_array($role->getRole(), $grantedRoles)) {
                $rolesChoice_attr[$role->getNom()] = ['selected' => true];
            }
        }

        $form = $this->createFormBuilder($User)
            ->add('login', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre login'), 'label' => 'Login'))
            ->add('plainpassword', TextType::class, array('required' => false,'attr' => array('class' => 'form-control','placeholder' => 'Saisir votre mot de passe'), 'label' => 'Password'))
            ->add('roles', ChoiceType::class, array('mapped' => false, 'attr' => array('class' => 'form-control selectpicker'), 'choices' => $rolesChoices, 'choice_attr' => $rolesChoice_attr,'multiple'=>true,'expanded'=>false, 'label' => 'Contact principal'))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')))        
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $selectedroles = $form['roles']->getData();

            foreach ($roles as $role){
                if (in_array($role->getId(), $selectedroles)) {
                    if(!$User->getRolesCollection()->contains($role)) {
                    $User->addRole($role); }
                }
                else{
                    if($User->getRolesCollection()->contains($role)){
                    $User->removeRole($role);}
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($User);
            $em->flush();

            $this->addFlash('notice','Information utilisateur Modifiées');


           return $this->redirectToRoute('User_list');

        }

        return $this->render('User/userinfo.html.twig', array(
            'form' => $form->createView(),
            'roles' => $roles));
    }    


    /**
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_USER')")
     * @Route("/edituserinfo/{id}", name="User_edituserinfo")
     */
    public function edituserinfoAction($id,Request $request)
    {
        $User = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->find($id);   

        $roles = $this->getDoctrine()
                        ->getRepository('AppBundle:UserRole')
                        ->findAll(); 

        $rolesChoices = [];
        $rolesChoice_attr = [];
        $grantedRoles = $User->getRoles();

        foreach ($roles as $role){
            $rolesChoices[$role->getNom()] = $role->getId();
            if (in_array($role->getRole(), $grantedRoles)) {
                $rolesChoice_attr[$role->getNom()] = ['selected' => true];
            }
        }

        $form = $this->createFormBuilder($User)
            ->add('login', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre login'), 'label' => 'Login'))
            ->add('plainpassword', TextType::class, array('required' => false,'attr' => array('class' => 'form-control','placeholder' => 'Saisir votre mot de passe'), 'label' => 'Password'))
            ->add('roles', ChoiceType::class, array('required' => false,'mapped' => false, 'attr' => array('class' => 'form-control selectpicker'), 'choices' => $rolesChoices, 'choice_attr' => $rolesChoice_attr,'multiple'=>true,'expanded'=>false, 'label' => 'Contact principal'))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')))        
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            
            $selectedroles = $form['roles']->getData();



            foreach ($roles as $role){
                 
                if (in_array($role->getId(), $selectedroles)) {
                    if(!$User->getRolesCollection()->contains($role)) {
                    $User->addRole($role); }
                }
                else{
                    if($User->getRolesCollection()->contains($role)){
                    $User->removeRole($role);}
                }
            }

        
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $this->addFlash('notice','Information utilisateur Modifiées');


           $UserLoggedIn = $this->getUser();


            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('User_list');
            }
            else {
                return $this->redirectToRoute('User_voir',array('id' => $UserLoggedIn->getId()));
            }
        }

        return $this->render('User/userinfo.html.twig', array(
            'form' => $form->createView(),
            'User' => $User,
            'roles' => $User->getRolesCollection()));
    }    


    /**
     * @Route("/delete/{id}", name="User_delete")
     */
    public function deleteAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $User = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->find($id);

        return $this->render('User/delete.html.twig',array('User' => $User));
    }

    /**
     * @Route("/deleteConfirmed/{id}", name="User_delete_confirmed")
     */
    public function deleteConfirmedAction($id,Request $request)
    {
        // replace this example code with whatever you need
       $User = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->find($id);


        $em = $this->getDoctrine()->getManager();

        $em->remove($User);
        $em->flush();

        $this->addFlash('notice','User Effacé');

        return $this->redirectToRoute('User_list');

    }

    /**
     * @Route("/edit/{id}", name="User_edit")
     */
    public function editAction($id,Request $request)
    {
        $User = $this->getDoctrine()
                        ->getRepository('AppBundle:User')
                        ->find($id);

        $form = $this->createFormBuilder($User)
            ->add('civilite', ChoiceType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Choisir une civilité'),
                'choices' => array( 'M.' => "M.",
                                    'Mme' => "Mme",
                                    'Melle' => "Melle"),
                'label' => 'Civilité'))
            ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre prénom'), 'label' => 'Prénom'))
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre nom'), 'label' => 'Nom'))
            ->add('genre', ChoiceType::class, array('choices' => array('Femme' => true,'Homme' => false),'multiple'=>false,'expanded'=>true, 'label' => 'Genre'))
            ->add('poste', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre poste'), 'label' => 'Poste'))
            ->add('departement', ChoiceType::class, array('placeholder' => ' ','attr' => array('class' => 'selectpicker', 'data-live-search'=> 'true'), 
                'choices' => array( 'Commercial' => "Commercial",
                                    'Technique' => "Technique",
                                    'Recouvrement' => "Recouvrement",
                                    'Facturation' => "Facturation",
                                    'Logistique' => "Logistique",
                                    'Avant-Vente' => "Avant-Vente",
                                    'Comptable' => "Comptable"),
                'label' => 'Département'))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre email'), 'label' => 'Email'))
            ->add('telephone', TextType::class, array('attr' => array('class' => 'form-control','placeholder' => 'Saisir votre numéro de tel'), 'label' => 'Téléphone'))            
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')))        
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $this->addFlash('notice','User Mis à jour');


           $UserLoggedIn = $this->getUser();


            if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('User_list');
            }
            else {
                return $this->redirectToRoute('User_voir',array('id' => $UserLoggedIn->getId()));
            }
        }

        return $this->render('User/edit.html.twig',array('form' => $form->createView(),'User' => $User));
    }

}



