<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
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
        $builder->add('date', DateType::class, [
            'label' => 'Date',
            'widget' => 'single_text',
            'required' => true,
        ])
            ->add('objet')
            ->add('client')
            ->add('tel')
            ->add('description')
            ->add('priority')
            ->add('serie')
            ->add('file', FileSdType::class, array('label' => 'Piece jointe'))
            ->add('affectedTo');
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
