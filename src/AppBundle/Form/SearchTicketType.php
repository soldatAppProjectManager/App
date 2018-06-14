<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTicketType extends AbstractType
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SearchTicketType constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('startDate', DateTimeType::class, [
            'attr' => ['class' => 'datepicker'],
            'label' => 'De',
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'required' => false,
        ])
            ->add('endDate', DateTimeType::class, [
                'attr' => ['class' => 'datepicker'],
                'label' => 'à',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Technicien',
                'required' => false,
            ])->add('Customer', EntityType::class, [
                'class' => Client::class,
                'label' => 'Client',
                'required' => false,
                'attr' => [
                    'class' => 'selectpicker',
                    'title' => 'Selectionner un client',
                    'data-live-search' => 'true'
                ],
                'query_builder' => function () {
                    return $this->entityManager->createQueryBuilder()
                        ->select('c')
                        ->from('AppBundle:Client', 'c')
                        ->innerJoin('AppBundle:Ticket', 't', Join::WITH, 'c.id = t.client');
                }
            ])
            ->add('submit', SubmitType::class, ['label' => 'chercher', 'attr' => ['class' => 'btn-success']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Search\TicketCriteria'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'search_ticket';
    }

}