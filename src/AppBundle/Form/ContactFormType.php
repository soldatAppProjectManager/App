<?php

namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Devis;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ContactFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('civilite',  ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Indiquer la civilité'),
                'choices' => array( 'M.' => 'M.', 
                                    'Mme' => 'Mme',
                                    'Melle' => 'Melle',
                                    'Dr.' => 'Dr.',
                                    'Me' => 'Me'), 'label' => 'Civilités'))
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Nom'))
            ->add('prenom', TextType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Prénom'))
            ->add('genre', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Préciser le genre du contact'),
                'choices' => array( 'Femme' => true, 
                                    'Homme' => 0), 'label' => 'Genre'))
            ->add('poste', TextType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Poste'))
            ->add('departement', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Choisir le departement'),
                'choices' => array( 'Achat' => 'Achat',
                                    'Bureautique' => 'Bureautique',
                                    'BU' => 'BU',
                                    'Channel' => 'Channel',
                                    'Commercial' => 'Commercial',
                                    'Infrastructure' => 'Infrastructure',
                                    'Informatique' => 'Informatique',
                                    'Marketing' => 'Marketing',
                                    'Réseau' => 'Réseau', 
                                    'Sécurité' => 'Sécurité'),
                'label' => 'Département'))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Email'))
            ->add('telephone', TextType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Téléphone'))
            ->add('defaut', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Est-ce le contact principal ?'),
                'choices' => array( 'Oui' => true, 
                                    'Non' => false), 'label' => 'Contact principal'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\contact'
        ]);
    }
}