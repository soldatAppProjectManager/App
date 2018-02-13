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
use AppBundle\Entity\BonDeCommandeClient;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BonDeCommandeClientFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datedereception', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('datebondecommande', DateType::class, ['widget' => 'single_text'])
            ->add('Fichier', FileType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'filestyle',
                    'data-buttonText' => ' Choisir',
                    'data-buttonName' => "btn-success"
                ]])
            ->add('numeroDeBonDeCommandeClient', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Référence du BC'
                ]
            ])
            ->add('echeance', IntegerType::class, [
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Durée en jours'
                    ]]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\BonDeCommandeClient'
        ]);
    }

}

