<?php

namespace Booking\AppBundle\Form;

use Booking\AppBundle\Entity\Book;
use Booking\AppBundle\Entity\Client;
use Booking\AppBundle\Entity\Core\Agent;
use Booking\AppBundle\Form\Core\AgentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Booking\AppBundle\Form\Metadata\ProductType as ProductMetType;

class BookType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                'allow_add'    => true,
                'allow_delete' => true,
                'label' => 'Products'
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\AppBundle\Entity\Book'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_book';
    }

}
