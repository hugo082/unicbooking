<?php

namespace Booking\AppBundle\Form\Metadata\Service;

use Booking\AppBundle\Entity\Metadata\Service\Limousine as LimousineMet;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LimousineType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pick_up', TextType::class, [
                'label' => 'Pick Up',
                'required' => false
            ])
            ->add('drop_off', TextType::class, [
                'label' => 'Drop Off',
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
            'data_class' => LimousineMet::class
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
