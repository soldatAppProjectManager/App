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
use AppBundle\Entity\Client;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ClientDevisFormType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('nom', TextType::class, array('label' => 'Nom du client '))
            ->add('secteur', ChoiceType::class, array('attr' => array('class' => 'form-control selectpicker','title' => 'Selectionner un secteur d\'activité'),
                'choices' => array( 'Aerospace industry' => "Aerospace industry", 
                                    'Agriculture' => "Agriculture", 
                                    'Chemical industry' => "Chemical industry", 
                                    'Computer industry' => "Computer industry", 
                                    'Construction industry' => "Construction industry", 
                                    'Defense industry' => "Defense industry", 
                                    'Education industry' => "Education industry", 
                                    'Energy industry' => "Energy industry", 
                                    'Entertainment industry' => "Entertainment industry", 
                                    'Financial services industry' => "Financial services industry", 
                                    'Food industry' => "Food industry", 
                                    'Health care industry' => "Health care industry", 
                                    'Hospitality industry' => "Hospitality industry", 
                                    'Information industry' => "Information industry", 
                                    'Manufacturing' => "Manufacturing", 
                                    'Mass media' => "Mass media", 
                                    'Particulier' => "Particulier", 
                                    'Telecommunications industry' => "Telecommunications industry", 
                                    'Transport industry' => "Transport industry", 
                                    'Water industry' => "Water industry"), 
                'label' => 'Secteur d\'activité'))
            ->add('delaipaiementconstate', IntegerType::class)
            ->add('prive', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 'Indiquer s\'sil s\'agit d\'un client public'),
                'choices' => array( 'Public' => false, 
                                    'Privé' => true), 'label' => 'Type de client'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Client'
        ]);
    }
}