<?php

namespace AppBundle\Form;

use AppBundle\Entity\Lot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RfpType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateTimeType::class, [
            'label' => 'Date d\'ouverture des plis',
            'attr' => ['class' => 'datetimepicker'],
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy HH:mm',
            'required' => true,
        ])
            ->add('lots', CollectionType::class, [
            'label' => 'Lots',
            'entry_type' => LotType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'by_reference' => false,
        ])
            ->add('number')
            ->add('modele', null, ['label' => 'Modele'])
            ->add('object');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Rfp'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'rfp';
    }


}
