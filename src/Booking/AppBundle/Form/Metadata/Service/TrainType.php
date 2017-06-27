<?php

namespace Booking\AppBundle\Form\Metadata\Service;

use Booking\AppBundle\Entity\Metadata\Service\Train as TrainMet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code',
                'required' => false
            ])
            ->add('station', TextType::class, [
                'label' => 'Station',
                'required' => false
            ])
            ->add('time', TimeType::class, [
                'label' => "Time",
                'required' => false,
                'widget' => 'single_text',
                'input' => 'string'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TrainMet::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_metadata_airport';
    }


}
