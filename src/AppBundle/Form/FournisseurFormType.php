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
use AppBundle\Entity\Fournisseur;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\PercentType; 

class FournisseurFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('nom', TextType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Nom du fournisseur '))
            ->add('fa', PercentType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Frais d Approches '))
            ->add('faSurtaxe', PercentType::class, array('attr' => array('class' => 'form-control'), 'label' => 'Frais d Approches surtaxés '))
            ->add('termepaiement', IntegerType::class, array('attr' => array('class' => 'form-control')))
            ->add('devisevente', EntityType::class, array('class' => 'AppBundle:Monnaie','attr' => array('class' => 'form-control'), 'label' => 'Devise de vente '));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Fournisseur'
        ]);
    }
}