<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Doctrine\ORM\EntityRepository;

class AirportType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, array(
            'label' => 'Name'
        ))
        ->add('code', TextType::class, array(
            'label' => 'Code'
        ))
        ->add('compagny', EntityType::class, array(
            'class' => 'AppBundle:Compagny',
            'placeholder' => 'book.form.select.placeholder',
            'choice_label' => function ($p) {return $p->getFullName();},
            'label' => 'Airline',
            'required' => false,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                ->where('e.removed = :rm')
                ->setParameter('rm', false );
            }
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Airport'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'appbundle_airport';
    }


}
