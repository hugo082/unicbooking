<?php

namespace Booking\AppBundle\Form\Core;

use Booking\AppBundle\Entity\Flight;
use Booking\AppBundle\Form\DataTransformer\CodeToFlightTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlightType extends AbstractType
{
    const OPTION_FLIGHT_TYPE = "data_form_flight_type";

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options[self::OPTION_FLIGHT_TYPE]) {
            $builder->add('type', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    Flight::TYPE_ARR[0] => Flight::TYPE_ARR[1],
                    Flight::TYPE_DEP[0] => Flight::TYPE_DEP[1]
                ]
            ]);
        }
        $builder
            ->add('number', TextType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Flight Code',
                ]
            ])
            ->add('id', HiddenType::class, []);


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Flight::class,
            self::OPTION_FLIGHT_TYPE => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_flight';
    }
}
