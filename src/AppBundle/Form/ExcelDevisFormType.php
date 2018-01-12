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

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Devis;

class ExcelDevisFormType extends AbstractType
{
	private $EntityManager;

    public function __construct(EntityManagerInterface $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $devis=$builder->getData();
    	$builder
            ->add('titre', TextType::class, array('attr' => array('placeholder' => 'Saisir un titre de devis explicite')))
            ->add('validite', IntegerType::class, array('data' => 15, 
                                                        'label' => 'Validité en Jours'))
            ->add('introduction', EntityType::class, array('class' => 'AppBundle:Entete','attr' => array('class' => 'selectpicker')))
            ->add('client', EntityType::class, array('class' => 'AppBundle:Client',
                                                    'query_builder' =>  $this->getEntityManager()->getRepository('AppBundle:Client')->trouverSesClientsQB($devis->getCommercial()->getId()),
                                                    'attr' => array('class' => 'selectpicker','title' => 'Selectionner un client','data-live-search'=>'true')))
            
            ->add('destinataire', EntityType::class, array('class' => 'AppBundle:contact'))
            ->add('fichier', FileType::class, array('mapped' => false,'attr' => array('class' => 'filestyle','data-buttonText' => ' Choisir','accept' => ".xls, .xlsx, .xlsm", 'data-buttonName' => "btn-success")))
            ->add('modele', EntityType::class, array('class' => 'AppBundle:modeleDevis','data' => 7,'attr' => array('class' => 'selectpicker')))
            ->add('devisevente', EntityType::class, array(  'class' => 'AppBundle:Monnaie','data' => 3,
                                                             'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Monnaie')->findAllByAscNom(),
                                                            'attr' => array('class' => 'selectpicker','title' => 'Selectionner la devise d\'achat')))
            ->add('piedDePage', EntityType::class, array('class' => 'AppBundle:piedDePage','choice_label' => 'titre','attr' => array('class' => ' selectpicker ')));
    }
   
    public function getEntityManager(){
        return $this->EntityManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Devis'
        ]);
    }
}