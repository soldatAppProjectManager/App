<?php

namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\ProduitBC;


class ProduitBCFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder
            ->add('quantite', IntegerType::class, array('attr' => array('class' => 'form-control quantite')))
            ->add('designation', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control','rows'=> '5', 'cols' => '200')))
            ->add('fournisseur', EntityType::class, array('class' => 'AppBundle:Fournisseur','choice_label' => 'nom','attr' => array('class' => 'form-control fournisseur')))
            ->add('prixachatht', MoneyType::class, array('attr' => array('class' => 'form-control prixdachat impactprix'), 'label' => 'Prix d Achat H.T. '))
            ->add('fraisapproche', PercentType::class, array('attr' => array('class' => 'form-control fa impactprix')))
            ->add('tauxTVA', PercentType::class, array('attr' => array('class' => 'form-control', 'value' => 20), 'label' => 'Taux de TVA'))
            ->add('deviseachat', EntityType::class, array('class' => 'AppBundle:Monnaie','choice_label' => 'nom','attr' => array('class' => 'form-control devise')))
            ->add('tauxAchat', NumberType::class, array('attr' => array('class' => 'form-control tauxachat impactprix','placeholder' => "Taux de change")))
            ->add('marge', PercentType::class, array('attr' => array('class' => 'form-control marge impactprix')))
            ->add('referenceoffre', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('metier', EntityType::class, array('class' => 'AppBundle:Metier','choice_label' => 'nom','attr' => array('class' => 'form-control')))
            ->add('typeproduit', EntityType::class, array('class' => 'AppBundle:TypeProduit','choice_label' => 'nom','attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-primary')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ProduitBC'
        ]);
    }

}

