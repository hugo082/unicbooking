<?php

namespace Booking\AppBundle\Form\Metadata;

use Booking\AppBundle\Entity\Metadata\Product as ProductMet;
use Booking\AppBundle\Entity\Product;
use Booking\AppBundle\Form\Metadata\Service\AirportType;
use Booking\AppBundle\Form\Metadata\Service\LimousineType;
use Booking\AppBundle\Form\Metadata\Service\TrainType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('date', DateType::class, [
                'label' => 'book.form.date',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'widget' => 'single_text'
            ])
            ->add('product_type', EntityType::class, [
                'class' => 'BookingAppBundle:Product',
                'choice_label' => 'name',
                'choice_attr' => function (Product $p) {
                    return ["s-type" => $p->getService()->getTypeLower()];
                }
            ])
            ->add('airport', AirportType::class, [
                'label' => false
            ])
            ->add('limousine', LimousineType::class, [
                'label' => false
            ])
            ->add('train', TrainType::class, [
                'label' => false
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Note',
                'required' => false
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductMet::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_metadata_product';
    }


}
