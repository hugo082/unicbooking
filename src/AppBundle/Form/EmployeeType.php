<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;

class EmployeeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('firstname', TextType::class, array(
            'label' => 'admin.driver_add.form.fname'
        ))
        ->add('lastname', TextType::class, array(
            'label' => 'admin.driver_add.form.lname'
        ))
        ->add('phone', TextType::class, array(
            'label' => 'admin.driver_add.form.phone'
        ))
        ->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Driver' => 'Driver',
                'Greeter' => 'Greeter'
            ),
            'placeholder' => 'book.form.select.placeholder',
            'label' => 'Type'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employee'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employee';
    }


}
