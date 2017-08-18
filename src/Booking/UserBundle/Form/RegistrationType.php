<?php

namespace Booking\UserBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phonenumber', TextType::class, [
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Phone number',
                )
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Driver' => 'ROLE_DRIVER',
                    'Greeter' => 'ROLE_GREETER',
                    'Admin' => 'ROLE_ADMIN'
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles'
            ])
        ;
    }

    public function getParent()
    {
        return RegistrationFormType::class;
    }

    public function getBlockPrefix()
    {
        return 'booking_user_registration';
    }
}
