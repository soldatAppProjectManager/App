<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProduitBC;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReceptionProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantite', IntegerType::class,['attr' => ['class' => 'qteRecu' ]])
            ->add('lieuStock', null, ['required'   => false])
            ->add('numSeries', TextType::class, array(
                "label" => "Numéros de série",
                'required'   => false,
                'attr' => ['class' => 'numSeries']
            ))
            ->add('produit', EntityType::class, [
                    'class' => ProduitBC::class,
                    'label' => ' ',
                    'attr' => ['class' => 'hidden']]
            )
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ReceptionProduit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_receptionproduit';
    }


}
