<?php

namespace Booking\AppBundle\Form;

use AppBundle\Entity\Airport;
use AppBundle\Entity\Book;
use AppBundle\Entity\Flight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\InternationalCodes\InternationalCodes;

class InternationalCodesType extends AbstractType
{
    const PRECISION_FULL = "prec_full";
    const PERCISION_NUMBER = "prec_num";

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['precision'] == self::PRECISION_FULL) {
            $builder
                ->add('icao', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        "placeholder" => 'codes.icao'
                    )
                ))
                ->add('iata', TextType::class, array(
                    'label' => false,
                    'attr' => array(
                        "placeholder" => 'codes.iata'
                    )
                ))
            ;
        } else {
            $builder
                ->add('code', TextType::class, array(
                    'label' => 'codes.all'
                ))
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('data_class');
        $resolver->setRequired('precision');
        $resolver->addAllowedValues('precision', array(self::PERCISION_NUMBER, self::PRECISION_FULL));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_internationalcodes';
    }

}
