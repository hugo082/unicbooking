<?php

namespace Booking\AppBundle\Form\Metadata;

use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Entity\Subcontractor;
use Booking\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

class ProductSubMetadataType extends AbstractType
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
            ->add('subcontractor', EntityType::class, array(
                'class' => Subcontractor::class,
                'empty_data' => null,
                'choice_label' => function (Subcontractor $e) {return $e->getName();},
                'required' => false,
                'label' => false,
                'placeholder' => '- Subcontractor -'
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class
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
