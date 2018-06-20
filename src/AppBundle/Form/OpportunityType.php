<?php

namespace AppBundle\Form;

use AppBundle\Entity\Opportunity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

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
            ->add('customer', null,
                ['label' => 'Client',
                    'attr' => [
                        'class' => 'selectpicker',
                        'title' => 'Selectionner un client',
                        'data-live-search' => 'true'
                    ],
                ])
            ->add('contact', null, ['label' => 'Contact'])
            ->add('type')
            ->add('status', null, ['label' => 'Statut'])
            ->add('acquisitionMode', null , ['label' => 'Mode d\'acquisition'])
            ->add('probability',null , ['label' => 'Probabilité'])
            ->add('products', CollectionType::class, [
                'label' => 'Produits',
                'entry_type' => OProductType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('dateEcheance', DateTimeType::class, [
                'required' => true,
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'datepicker'
                ]
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
