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
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Doctrine\ORM\EntityManagerInterface;

class RapportFormType extends AbstractType
{

    private $EntityManager;

    public function __construct(EntityManagerInterface $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('statut', EntityType::class, array('class' => 'AppBundle:statutProduit','choice_label' => 'nom'))
            ->add('debut', DateType::class, array('widget' =>'single_text'))
            ->add('fin', DateType::class, array('widget' =>'single_text'))
            ->add('commercial', EntityType::class, array('class' => 'AppBundle:User','choice_label' => 'nom'))
            ->add('fournisseur', EntityType::class, array(  'class' => 'AppBundle:Fournisseur',
                                                            'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Fournisseur')->findAllByAscNom(),
                                                            'attr' => array('class' => 'selectpicker','data-live-search'=>'true','title' => 'Selectionner un Fournisseur')))

            ->add('metier', EntityType::class, array(   'class' => 'AppBundle:Metier',
                                                        'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Metier')->findAllByAscNom(),
                                                        'attr' => array('class' => 'selectpicker','title' => 'Selectionner un métier')))

            ->add('typeproduit', EntityType::class, array(  'class' => 'AppBundle:TypeProduit',
                                                            'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TypeProduit')->findAllByAscNom(),
                                                            'attr' => array('class' => 'selectpicker','title' => 'Selectionner le type de produit')))        
            ->add('client', EntityType::class, array('class' => 'AppBundle:Client','attr' => array('class' => 'selectpicker','title' => 'Selectionner un client','data-live-search'=>'true')));
    }

    
    public function getEntityManager(){
        return $this->EntityManager;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Tools\Rapport'
        ]);
    }
}