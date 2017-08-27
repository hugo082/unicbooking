<?php

namespace Booking\AppBundle\Form\Metadata\Service;

use Booking\AppBundle\Entity\Metadata\Service\Limousine as LimousineMet;
use Booking\AppBundle\Form\Metadata\ProductType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LimousineType extends AbstractType
{
    const OPTION_TYPE = "data_form_option_type";
    const TYPE_NEW = "new";
    const TYPE_API = ProductType::TYPE_API;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options[self::OPTION_TYPE];
        if ($type == self::TYPE_NEW || $type == self::TYPE_API)
            $this->buildNewForm($builder, $options);
        if ($type == self::TYPE_API)
            $this->buildApiForm($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LimousineMet::class,
            self::OPTION_TYPE => self::TYPE_NEW
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_metadata_airport';
    }

    private function buildNewForm(FormBuilderInterface $builder, array $options) {
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

    private function buildApiForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('start_mileage', TextType::class, [
                'label' => 'Start mileage',
                'required' => false
            ]);
    }

    static public function fromProductTypeForm(string $option) {
        if ($option == self::TYPE_API)
            return self::TYPE_API;
        return self::TYPE_NEW;
    }

}
