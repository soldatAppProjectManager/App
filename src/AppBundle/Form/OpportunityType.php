<?php

namespace AppBundle\Form;

use AppBundle\Entity\Opportunity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OpportunityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', null, ['label' => 'Nom du projet'])
            ->add('comment', null, ['label' => 'Description'])
            ->add('salesEngineer', null, ['label' => 'Commercial'])
            ->add('preSale', null, ['label' => 'Avant vente'])
            ->add('customer', null, ['label' => 'Client'])
            ->add('type')
            ->add('technologies')
            ->add('products', CollectionType::class, [
                'label' => 'Produits',
                'entry_type' => OProductType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Opportunity::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'opportunity';
    }


}
