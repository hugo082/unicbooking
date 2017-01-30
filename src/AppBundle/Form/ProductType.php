<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Doctrine\ORM\EntityRepository;

class ProductType extends AbstractType
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
        ->add('price', IntegerType::class, array(
            'label' => 'Price (EUR)'
        ))
        ->add('additionalprice', IntegerType::class, array(
            'label' => 'Additional Pax Price (EUR)',
            'required' => false
        ))
        ->add('passengers', IntegerType::class, array(
            'label' => 'Passengers'
        ))
        ->add('transport', ChoiceType::class, array(
            'choices'  => array(
                'YES' => true,
                'NO' => false
            ),
            'placeholder' => 'book.form.select.placeholder',
            'label' => 'Transport'
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
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'appbundle_product';
    }


}
