<?php

namespace Booking\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Air' => 'Air',
                    'Travel Agent' => 'Travel Agent',
                    'Corporate' => 'Corporate',
                    'Private' => 'Private',
                    'Embassy' => 'Embassy',
                    'Ministry' => 'Ministry',
                ),
            ))
            ->add('billing', ChoiceType::class, array(
                'choices'  => array(
                    'HT' => 'HT',
                    'TTC' => 'TTC',
                ),
            ))
            ->add('tariff', ChoiceType::class, array(
                'choices'  => array(
                    'Public' => 'Public',
                    'Corporate' => 'Corporate',
                ),
            ))
            ->add('contacts', CollectionType::class, array(
                'entry_type'   => ContactType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => 'Contacts'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_client';
    }


}
