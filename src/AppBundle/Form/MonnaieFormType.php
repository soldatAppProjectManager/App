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
use AppBundle\Entity\Monnaie;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class MonnaieFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('nom', TextType::class)
            ->add('Symbol', TextType::class)
            ->add('Code', TextType::class)
            ->add('tauxAchat', TextType::class)
            ->add('tauxVente', TextType::class)
            ->add('commission', PercentType::class)
            ->add('volatilite', PercentType::class)
            ->add('periode', IntegerType::class)
            ->add('dateCreation', DateType::class, array('widget' =>'single_text'))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Monnaie'
        ]);
    }

}

