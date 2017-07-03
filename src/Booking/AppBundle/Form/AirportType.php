<?php

namespace Booking\AppBundle\Form;

use Booking\AppBundle\Entity\InternationalCodes\AirportsCodes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('name')
            ->add('supported', CheckboxType::class, array(
                'label' => "Supported",
                'required' => false
            ))
            ->add('codes', InternationalCodesType::class, array(
            "data_class" => AirportsCodes::class,
                "precision" => InternationalCodesType::PRECISION_FULL
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\AppBundle\Entity\Airport'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_airport';
    }


}
