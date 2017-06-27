<?php

namespace Booking\AppBundle\Form\Metadata\Service;

use Booking\AppBundle\Entity\Metadata\Service\Airport as AirportMet;
use Booking\AppBundle\Form\Core\FlightType as CoreFlightType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AirportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('flight', CoreFlightType::class, [
                'label' => 'Flight',
                'required' => false
            ])
            ->add('flight_transit', CoreFlightType::class, [
                'label' => 'Flight Transit',
                'required' => false
            ])
            /*
            ->add('greeter', CheckboxType::class, [
                'label' => 'Add greeter',
                'required' => false
            ])
            */
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AirportMet::class
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
