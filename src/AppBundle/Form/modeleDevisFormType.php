<?php

namespace AppBundle\Form;

use AppBundle\Entity\modeleDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;

class modeleDevisFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('description', TextType::class)
            ->add('langue', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Préciser la langue'),
                'choices' => array( 'Français' => 'Français', 
                                    'Anglais' => 'Anglais'), 'label' => 'Langue'))
            ->add('miseenpage', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Mise en Page'),
                'choices' => array( 'Portrait' => 'portrait', 
                                    'Landscape' => 'landscape'), 'label' => 'Langue'))
            ->add('modele',  FileType::class, array('attr' => array('class' => 'filestyle','data-buttonText' => ' Choisir', 'data-buttonName' => "btn-success")))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => modeleDevis::class,
        ));
    }
}