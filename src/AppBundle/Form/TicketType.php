<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', null, [
            'label' => 'Date',
            'attr' => ['class' => 'datetimepicker'],
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy HH:mm',
            'required' => true,
        ])
            ->add('objet')
            ->add('client', null,
                ['label' => 'Client',
                    'attr' => [
                        'class' => 'selectpicker',
                        'title' => 'Selectionner un client',
                        'data-live-search' => 'true'
                    ],
                ])
            ->add('tel', null, ['label' => 'Téléphone'])
            ->add('description')
            ->add('priority', null, ['label' => 'Priorité'])
            ->add('serie', null,
                ['label' => 'Numéro de série',
                    'attr' => [
                        'class' => 'selectpicker',
                        'title' => 'Selectionner un numéro de série',
                        'data-live-search' => 'true'
                    ],
                ])
            ->add('file', FileSdType::class, array('label' => 'Pièce jointe'))
            ->add('affectedTo', null, ['label' => 'Affecté à']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_ticket';
    }


}
