<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\Serie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantite')
            ->add('series', null,[
                'class' => Serie::class,
                'attr' => ['class' => 'js-example-basic-multiple']
            ])
            ->add('produit', EntityType::class, [
                    'class' => ProduitBC::class,
                    'label' => ' ',
                    'attr' => ['class' => 'hidden']]
            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\LivraisonProduit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_livraisonproduit';
    }


}
