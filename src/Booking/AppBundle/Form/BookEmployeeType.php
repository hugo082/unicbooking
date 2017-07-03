<?php

namespace Booking\AppBundle\Form;

use Booking\AppBundle\Entity\Book;
use Booking\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

class BookEmployeeType extends AbstractType
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
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Book::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_book';
    }


}
