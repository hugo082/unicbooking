<?php
/**
 * Created by PhpStorm.
 * User: hugofouquet
 * Date: 07/08/2017
 * Time: 12:51
 */

namespace Booking\UserBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', EntityType::class, [
                'label' => 'Location',
                'placeholder' => '- Location -',
                'class' => 'BookingAppBundle:Location'
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

    public function getBlockPrefix()
    {
        return 'booking_user_registration';
    }
}
