<?php

namespace AppBundle\Form;

use AppBundle\Entity\Airport;
use AppBundle\Entity\Book;
use AppBundle\Entity\Flight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\InternationalCodes;

class InternationalCodesType extends AbstractType
{
    public const PRECISION_FULL = "prec_full";
    public const PERCISION_NUMBER = "prec_num";

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['precision'] == self::PRECISION_FULL) {
            $builder
                ->add('icao', TextType::class, array(
                    'label' => 'codes.icao'
                ))
                ->add('iata', TextType::class, array(
                    'label' => 'codes.iata'
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
        $resolver->setDefaults(array(
            'data_class' => InternationalCodes::class
        ));
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
