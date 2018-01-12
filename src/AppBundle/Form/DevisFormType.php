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

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Devis;

class DevisFormType extends AbstractType
{
	private $EntityManager;

    public function __construct(EntityManagerInterface $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $devis=$builder->getData();

        if (is_null($devis->getModele()))
            $builder->add('modele', EntityType::class, array('class' => 'AppBundle:modeleDevis','data' => 7,'attr' => array('class' => 'selectpicker')));
        else
            $builder->add('modele', EntityType::class, array('class' => 'AppBundle:modeleDevis','attr' => array('class' => 'selectpicker')));

    	$builder
            ->add('titre', TextType::class, array('attr' => array('placeholder' => 'Saisir un titre de devis explicite')))
            ->add('numdemande', TextType::class, array('attr' => array('placeholder' => 'Saisir un Numero de demande')))
            ->add('validite', IntegerType::class, array('label' => 'Validité en Jours'))
            ->add('introduction', EntityType::class, array('class' => 'AppBundle:Entete','attr' => array('class' => 'selectpicker')))
            ->add('client', EntityType::class, array('class' => 'AppBundle:Client',
                                                    'query_builder' =>  $this->getEntityManager()->getRepository('AppBundle:Client')->trouverSesClientsQB($devis->getCommercial()->getId()),
                                                    'attr' => array('class' => 'selectpicker','title' => 'Selectionner un client','data-live-search'=>'true')))
            
            ->add('destinataire', EntityType::class, array('class' => 'AppBundle:contact'))

            ->add('TravailLivraison', EntityType::class, array( 'class' => 'AppBundle:TravailLivraison',
                                                                'query_builder' =>  $this->getEntityManager()->getRepository('AppBundle:TravailLivraison')->findAllByDescCharge(),
                                                                'attr' => array('class' => 'selectpicker travail')))

            ->add('TravailAvantVente', EntityType::class, array('class' => 'AppBundle:TravailAvantVente',
                                                                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TravailAvantVente')->findAllByDescCharge(),
                                                                'attr' => array('class' => 'selectpicker  travail')))

            ->add('TravailCommercial', EntityType::class, array('class' => 'AppBundle:TravailCommercial',
                                                                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TravailCommercial')->findAllByDescCharge(),
                                                                'attr' => array('class' => 'selectpicker travail')))

            ->add('TravailImport', EntityType::class, array('class' => 'AppBundle:TravailImport',
                                                                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TravailImport')->findAllByDescCharge(),
                                                                'attr' => array('class' => 'selectpicker travail')))

            ->add('termes', EntityType::class, array(   'label' => 'Conditions générales','class' => 'AppBundle:TermeCommercial',
                                                        'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TermeCommercial')->findAllByAscNom(),
                                                        'attr' => array('class' => 'form-control selectpicker','title' => 'Selectionner les condition'),
                                                        'multiple'=>true,'expanded'=>false))
            ->add('devisevente', EntityType::class, array(  'class' => 'AppBundle:Monnaie',
                                                             'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Monnaie')->findAllByAscNom(),
                                                            'attr' => array('class' => 'selectpicker')))
            ->add('tauxVente', NumberType::class, array('attr' => array('class' => 'impactprix','placeholder' => "Taux de change Devise Vente")))

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