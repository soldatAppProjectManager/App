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
use AppBundle\Entity\CategorieTerme;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class CategorieTermeFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('nom', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\CategorieTerme'
        ]);
    }

}

