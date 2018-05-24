<?php

namespace AppBundle\Form;

use AppBundle\Entity\TicketHistory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketHistoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var TicketHistory $data */
        $data = $builder->getData();
        $builder
            ->add('date', null, [
                'label' => 'Date',
                'attr' => ['class' => 'datetimepicker'],
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm',
                'required' => true,
            ])
            ->add('note')
            ->add('affectedTo',null,  ['label' => 'Affecté à'])
        ;

        if($data->getStatus()) {
            $builder->add('status', null, ['disabled' => true]);
        } else {
            $builder->add('status');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\TicketHistory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_tickethistory';
    }


}
