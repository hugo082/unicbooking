<?php

namespace Booking\AppBundle\Form;

use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Client;
use Booking\AppBundle\Entity\Core\Agent;
use Booking\AppBundle\Form\Core\AgentType;
use Booking\AppBundle\Form\Core\TaxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Booking\AppBundle\Form\Metadata\ProductType as ProductMetType;

class BookType extends AbstractType
{
    const OPTION_TYPE = "data_form_type";
    const TYPE_NEW = "new";
    const TYPE_TAXES = "taxes";

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options[self::OPTION_TYPE];
        if ($type == self::TYPE_NEW) {
            $this->buildNewForm($builder, $options);
        } else if ($type == self::TYPE_TAXES) {
            $this->buildTaxesForm($builder, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Book::class,
            self::OPTION_TYPE => self::TYPE_NEW
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_book';
    }

    private function buildTaxesForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('products', CollectionType::class, array(
                'entry_type'   => ProductMetType::class,
                'entry_options'  => [
                    ProductMetType::OPTION_TYPE => ProductMetType::TYPE_PRICE,
                ],
                'label' => 'Products prices'
            ))
            ->add('taxes', CollectionType::class, array(
                'entry_type'   => TaxType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => 'Taxes'
            ))
        ;
    }

    private function buildNewForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('agent', AgentType::class, [
                'label' => 'Agent'
            ])
            ->add('client', EntityType::class, array(
                'class'   => Client::class,
                'placeholder' => '- Client -',
                'choice_label' => 'name',
                'label' => false,
                'choice_attr' => function (Client $client) {
                    return ["c-id" => $client->getId()];
                }
            ))
            ->add('devices', ChoiceType::class, array(
                'choices'  => array(
                    'EUR' => 'EUR',
                    'USD' => 'USD',
                    'GBP' => 'GBP'
                ),
                'label' => false,
                'attr' => array(
                    'class' => 'col-md-1 col-sm-1 col-lg-1 col-xs-1'
                )
            ))
            ->add('products', CollectionType::class, array(
                'entry_type'   => ProductMetType::class,
                'entry_options'  => array(
                    ProductMetType::OPTION_TYPE => ProductMetType::TYPE_NEW,
                ),
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => 'Products'
            ))
        ;
    }

}
