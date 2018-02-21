<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lieu', null, [
                'label' => 'Lieu de livraison'
            ])
            ->add('livreur', null, [
                'label' => 'Nom du livreur'
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de livraison',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('livraisonProduits', CollectionType::class, [
                'entry_type' => LivraisonProduitType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('Enregistrer', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary'),
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Livraison'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_livraison';
    }


}
