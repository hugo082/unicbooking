<?php

namespace Booking\AppBundle\Form\Metadata;

use Booking\AppBundle\Entity\Metadata\Product as ProductMet;
use Booking\AppBundle\Entity\Product;
use Booking\AppBundle\Form\Core\PriceType;
use Booking\AppBundle\Form\CustomerType;
use Booking\AppBundle\Form\Metadata\Service\AirportType;
use Booking\AppBundle\Form\Metadata\Service\LimousineType;
use Booking\AppBundle\Form\Metadata\Service\TrainType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    const OPTION_TYPE = "data_form_option_type";
    const TYPE_NEW = "new";
    const TYPE_SUB_MET = "sub_met";
    const TYPE_PRICE = "price";

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options[self::OPTION_TYPE];
        if ($type == self::TYPE_NEW) {
            $this->buildNewForm($builder, $options);
        } elseif ($type == self::TYPE_PRICE) {
            $this->buildPriceForm($builder, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ProductMet::class,
            self::OPTION_TYPE => self::TYPE_NEW
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_metadata_product';
    }

    private function buildPriceForm(FormBuilderInterface $builder, array $options) {
        $builder->add('customPrice', IntegerType::class, [
            'label' => false,
            'required' => false,
            'attr' => [ "placeholder" => "Custom price" ]
        ]);
    }

    private function buildNewForm(FormBuilderInterface $builder, array $options) {
        $builder->add('date', DateType::class, [
            'label' => 'Date',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'widget' => 'single_text'
        ])
            ->add('product_type', EntityType::class, [
                'label' => 'Product Type',
                'class' => 'BookingAppBundle:Product',
                'choice_attr' => function (Product $p) {
                    $ids = "";
                    foreach ($p->getClients() as $client)
                        $ids .= $client->getId()."-";
                    $ids = substr($ids, 0, -1);
                    return [
                        "s-type" => $p->getService()->getTypeLower(),
                        "c-ids" => $ids
                    ];
                }
            ])
            ->add('location', EntityType::class, [
                'label' => 'Location',
                'placeholder' => '- Location -',
                'class' => 'BookingAppBundle:Location'
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
                'attr' => [
                    'placeholder' => 'Note',
                ],
                'required' => false
            ])
            ->add('baggages', ChoiceType::class, [
                'label' => 'Baggages',
                'required' => true,
                'choices'  => [
                    '0 bag' => 0,
                    '1 bag' => 1,
                    '2 bags' => 2,
                    '3 bags' => 3,
                    '4 bags' => 4,
                    '5 bags' => 5,
                    '6 bags' => 6,
                    '7 bags' => 7,
                    '8 bags' => 8,
                    '9 bags' => 9,
                    '10 bags' => 10,
                    '11 bags' => 11,
                    '12 bags' => 12,
                    '13 bags' => 13,
                    '14 bags' => 14,
                    '15 bags' => 15,
                    '16 bags' => 16,
                    '17 bags' => 17,
                    '18 bags' => 18,
                    '19 bags' => 19,
                    '20 bags' => 20,
                ]
            ])
            ->add('customers', CollectionType::class, array(
                'entry_type'   => CustomerType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'prototype_name' => '__name_cust__',
                'label' => 'Customers'
            ));
    }

}
