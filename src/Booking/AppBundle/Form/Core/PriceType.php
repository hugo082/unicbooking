<?php

namespace Booking\AppBundle\Form\Core;

use Booking\AppBundle\Entity\Core\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('count', IntegerType::class, [
                    'label' => "Count (HT)",
                    'attr' => [
                        'placeholder' => 'Count',
                    ]]
            );

        if ($options["tva"])
            $builder->add('tva', IntegerType::class, array(
                    'attr' => array(
                        'placeholder' => 'TVA',
                    ))
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Price::class,
            'tva' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_price';
    }
}
