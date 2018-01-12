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

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\piedDePage;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class piedDePageFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('titre', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('adresse', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('telephone', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('fax', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('email', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('siteweb', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('formejuridique', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('capital', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('rc', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('patente', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('ice', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('cnss', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\piedDePage'
        ]);
    }
}

