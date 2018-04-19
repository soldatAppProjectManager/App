<?php

namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use \Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\ProduitFusion;

class ProduitFusionFormType extends AbstractType
{

    public function __construct(EntityManagerInterface $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ProduitFusion=$builder->getData();

        if (isset($ProduitFusion))
            $builder->add('optionnel', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 's\'agit il d\'une option'),
                'choices' => array( 'Obligatoire' => false, 
                                    'Option' => true), 'label' => 'Produit fusion optionnel'));
        else
            $builder->add('optionnel', ChoiceType::class, array('attr' => array('class' => 'selectpicker','title' => 's\'agit il d\'une option'),'empty_data' => false,
                'choices' => array( 'Obligatoire' => false, 
                                    'Option' => true), 'label' => 'Produit fusion optionnel'));

        $builder
            ->add('quantite', IntegerType::class, array('attr' => array('class' => 'impactprix')))
            ->add('designation', TextType::class)
            ->add('unite', null, ['label' => 'Unité'])
            ->add('prixVenteHT', MoneyType::class, array('attr' => array('placeholder' => 'Saisir un prix','class' => 'impactprix'), 'label' => 'Prix de Vente H.T.'))
            ->add('description', TextareaType::class, array('attr' => array('rows'=> '10', 'cols' => '200')));        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    	$resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ProduitFusion'
        ]);
    }
}