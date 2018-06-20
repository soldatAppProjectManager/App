<?php
/**
 * Created by PhpStorm.
 * User: FOFANA
 * Date: 19/06/2018
 * Time: 15:50
 */

namespace AppBundle\Form;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchPeriodType extends AbstractType
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SearchPeriodType constructor
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('startDate', DateTimeType::class, [
            'required' => false,
            'label' => 'Du',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => ['class' => 'datepicker', 'autocomplete' => 'off'],
        ])
            ->add('endDate', DateTimeType::class, [
                'required' => false,
                'label' => 'Au',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => ['class' => 'datepicker', 'autocomplete' => 'off'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-success']
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Search\PeriodCriteria'
        ));
    }

    public function getBlockPrefix()
    {
        return 'search_period';
    }
}
