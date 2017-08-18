<?php

namespace Booking\AppBundle\Form;

use Booking\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubcontractorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('driver', EntityType::class, array(
                'class' => User::class,
                'empty_data' => null,
                'choice_label' => function (User $p) {return $p->getUsername();},
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_DRIVER"%' );
                },
                'required' => false,
                'label' => false,
                'placeholder' => '- Driver -'
            ))
            ->add('greeter', EntityType::class, array(
                'class' => User::class,
                'empty_data' => null,
                'choice_label' => function (User $p) {return $p->getUsername();},
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_GREETER"%' );
                },
                'required' => false,
                'label' => false,
                'placeholder' => '- Greeter -'
            ))
            ->add('name', TextType::class, [
                'label' => 'Name'
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Booking\AppBundle\Entity\Subcontractor'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'booking_appbundle_subcontractor';
    }


}
