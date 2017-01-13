<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CustomerType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', ChoiceType::class, array(
            'choices'  => array(
                'cust.form.title.mr' => 'MR',
                'cust.form.title.mrs' => 'MRS',
                'cust.form.title.ms' => 'MS',
                'cust.form.title.mstr' => 'MSTR',
                'na' => 'NA'
            ),
            'label' => false,
            'attr' => array(
                'class' => 'col-md-1 col-sm-1 col-lg-1 col-xs-1'
            )
        ))
        ->add('firstname', TextType::class, array(
            'label' => false,
            'attr' => array(
                'class' => 'col-md-2 col-sm-3 col-lg-3 col-xs-3',
                'placeholder' => 'cust.form.fname'
            )
        ))
        ->add('lastname', TextType::class, array(
            'label' => false,
            'attr' => array(
                'class' => 'col-md-2 col-sm-3 col-lg-3 col-xs-4',
                'placeholder' => 'cust.form.lname'
            )
        ))
        ->add('sexe', ChoiceType::class, array(
            'choices'  => array(
                'cust.form.sexe.male' => 'MA',
                'cust.form.sexe.femelle' => 'FE',
                'na' => 'NA'
            ),
            'label' => false,
            'attr' => array(
                'class' => 'col-md-2 col-sm-2 col-lg-2 col-xs-2'
            )
        ))
        ->add('phone', TextType::class, array(
            'label' => false,
            'attr' => array(
                'class' => 'col-md-2 col-sm-3 col-lg-3 col-xs-2',
                'placeholder' => 'cust.form.phone'
            ),
            'required' => false
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }
    
}
