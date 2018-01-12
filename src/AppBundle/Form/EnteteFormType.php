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
use AppBundle\Entity\Entete;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EnteteFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('titre', TextType::class)
            ->add('langue', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Selectionner une langue'),
                'choices' => array( 'Anglais' => "Anglais",  
                                    'Francais' => "Francais"), 
                'label' => 'Langue'))
            ->add('texte', TextareaType::class, array('attr' => array('rows'=> '20', 'cols' => '200')))
            ->add('genre', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Préciser le genre du contact'),
                'choices' => array( 'Femme' => true, 
                                    'Homme' => 0), 'label' => 'Genre'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Entete'
        ]);
    }

}

