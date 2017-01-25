<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FlightType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('number', IntegerType::class, array(
            'label' => 'book.flight.number'
        ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'book.form.dep' => 'DEP',
                'book.form.arr' => 'ARR'
            ),
            'placeholder' => 'book.form.select.placeholder',
            'label' => 'book.form.rqserv'
        ))
        ->add('deptime', TimeType::class, array(
            'label' => "book.form.timepu",
            'widget' => 'single_text',
            'input' => 'string'
        ))
        ->add('arrtime', TimeType::class, array(
            'label' => "book.form.timepu",
            'widget' => 'single_text',
            'input' => 'string'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Flight'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'appbundle_flight';
    }


}
