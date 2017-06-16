<?php

namespace Booking\AppBundle\Form;

use Booking\AppBundle\Form\Core\PriceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price', PriceType::class, array(
                "tva" => true
            ))
            ->add('service', EntityType::class, array(
                'class' => 'BookingAppBundle:Service',
                'choice_label' => 'name',
            ))
            ->add('clients', EntityType::class, array(
                'required' => false,
                'class' => 'BookingAppBundle:Client',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\AppBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_product';
    }


}
