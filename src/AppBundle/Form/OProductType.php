<?php

namespace AppBundle\Form;

use AppBundle\Entity\OProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('designation', null, ['label' => 'Désignation'])
            ->add('quantity', null, ['label' => 'Quantité'])
            ->add('price', null, ['label' => 'Prix HT'])
            ->add('trade', null, ['label' => 'Métier'])
            ->add('productType', null, ['label' => 'Type de produit']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => OProduct::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_oproduct';
    }


}
