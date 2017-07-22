<?php

namespace Booking\AppBundle\Form;

use Booking\AppBundle\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Doctrine\ORM\EntityRepository;

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
                    'Mr' => 'Mr',
                    'Mrs' => 'Mrs',
                    'Ms' => 'Ms',
                    'Mstr' => 'Mstr',
                    'Amb' => 'Amb',
                    'Col.' => 'Col.',
                    'Cpt' => 'Cpt',
                    'Dr.' => 'Dr.',
                    'Duke' => 'Duke',
                    'Eng' => 'Eng',
                    'Gen' => 'Gen',
                    'HE' => 'HE',
                    'HRH Prince' => 'HRH Prince',
                    'HRH Princess' => 'HRH Princess',
                    'Hon' => 'Hon',
                    'Lady' => 'Lady',
                    'Lord' => 'Lord',
                    'Miss' => 'Miss',
                    'Prof' => 'Prof',
                    'Rabbi' => 'Rabbi',
                    'Rev' => 'Rev',
                    'SHK' => 'SHK',
                    'SHKA' => 'SHKA',
                    'Sir' => 'Sir',
                    'N/A' => 'N/A'
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

            ->add('cabin', ChoiceType::class, array(
                'label' => false,
                'choices'  => array(
                    'Economy  ' => 'Economy',
                    'First' => 'First',
                    'Business' => 'Business'
                ),
                'attr' => array(
                    'class' => 'col-md-2 col-sm-3 col-lg-3 col-xs-3',
                    'placeholder' => 'Cabin'
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
            'data_class' => Customer::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_customer';
    }

}
