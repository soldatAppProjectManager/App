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
use AppBundle\Entity\ProduitDevis;

class ProduitDevisFormType extends AbstractType
{
    private $EntityManager;

    public function __construct(EntityManagerInterface $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $produit = $builder->getData();

        $builder
            ->add('catalogue', EntityType::class, array('mapped' => false, 'class' => 'AppBundle:ProduitDevis', 'choice_label' => 'reference', 'label' => 'Choisir un produit du catalogue',
                'attr' => array('class' => 'selectpicker', 'data-live-search' => 'true', 'title' => 'Selectionner un produit du catalogue')))
            ->add('quantite', IntegerType::class, array('attr' => array('class' => 'impactprix')))
            ->add('designation', TextType::class)
            ->add('description', TextareaType::class, array('attr' => array('rows' => '10', 'cols' => '200')))
            ->add('fournisseur', EntityType::class, array('class' => 'AppBundle:Fournisseur',
                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Fournisseur')->findAllByAscNom(),
                'attr' => array('class' => 'selectpicker', 'data-live-search' => 'true', 'title' => 'Selectionner un Fournisseur')))

            ->add('prixachatht', NumberType::class, array('attr' => array('placeholder' => 'Saisir un prix', 'class' => 'impactprix'), 'label' => 'Prix d Achat H.T.'))
            ->add('prixVenteHT', MoneyType::class, array('attr' => array('placeholder' => 'Prix de Vente H.T', 'class' => 'prixdevente'), 'label' => 'Prix de Vente H.T.'))

            ->add('fraisapproche', PercentType::class, array('scale' => 2, 'attr' => array('class' => 'impactprix')))
            ->add('tauxSpecial', ChoiceType::class, array('attr' => array('class' => 'selectpicker', 'title' => 'Taux de douane spécial ?'),
                'choices' => array('Standard' => false,
                    'Surtaxée' => true), 'data' => false, 'label' => 'Catégorie douanière'))
            ->add('tauxTVA', PercentType::class, array('attr' => array('value' => 20), 'scale' => 2, 'label' => 'Taux de TVA'))
            ->add('deviseachat', EntityType::class, array('class' => 'AppBundle:Monnaie',
                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Monnaie')->findAllByAscNom(),
                'attr' => array('class' => 'selectpicker', 'title' => 'Selectionner la devise d\'achat', 'disabled' => true)))
            ->add('tauxAchat', NumberType::class, array('attr' => array('class' => 'impactprix', 'placeholder' => "Taux de change")))
            ->add('marge', PercentType::class, array('attr' => array('class' => 'impactprix'), 'scale' => 2))
            ->add('optionnel', ChoiceType::class, array('attr' => array('class' => 'selectpicker', 'title' => 's\'agit il d\'une option'), 'data' => false,
                'choices' => array('Obligatoire' => false,
                    'Option' => true), 'label' => 'Produit optionnel'))
            ->add('cummulable', ChoiceType::class, array('attr' => array('class' => 'selectpicker', 'title' => 'est ce cummulable ?'), 'data' => true,
                'choices' => array('Cummulable' => true,
                    'Non cummulable' => false), 'label' => 'Produit cummulable'))
            ->add('referenceoffre', TextType::class)
            ->add('unite', null, ['label' => 'Unité'])
            ->add('referenceproduit', TextType::class)
            ->add('commercial', EntityType::class, array('class' => 'AppBundle:contact', 'query_builder' =>
                $this->getEntityManager()->getRepository('AppBundle:contact')->findThoseWhoWorkForSupplier()))
            ->add('dateoffre', DateType::class, array('widget' => 'single_text', 'attr' => array('class' => 'form-control')))
            ->add('metier', EntityType::class, array('class' => 'AppBundle:Metier',
                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:Metier')->findAllByAscNom(),
                'attr' => array('class' => 'selectpicker', 'title' => 'Selectionner un métier')))
            ->add('termes', EntityType::class, array('class' => 'AppBundle:TermeCommercial',
                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TermeCommercial')->findAllByAscNom(),
                'attr' => array('class' => 'form-control selectpicker', 'title' => 'Selectionner les termes'),
                'multiple' => true, 'expanded' => false))
            ->add('garanties', EntityType::class, array('class' => 'AppBundle:garantie',
                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:garantie')->findAllByAscNom(),
                'attr' => array('class' => 'form-control selectpicker', 'title' => 'Selectionner les garanties'),
                'multiple' => true, 'expanded' => false))
            ->add('typeproduit', EntityType::class, array('class' => 'AppBundle:TypeProduit',
                'query_builder' => $this->getEntityManager()->getRepository('AppBundle:TypeProduit')->findAllByAscNom(),
                'attr' => array('class' => 'selectpicker', 'title' => 'Selectionner le type de produit')));
    }

    public function getEntityManager()
    {
        return $this->EntityManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\ProduitDevis'
        ]);
    }
}