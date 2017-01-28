<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\CustomerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
            'class' => 'AppBundle:Employee',
            'choice_label' => function ($p) {return $p->getFullName();},
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                ->where('e.type = :typ')
                ->setParameter('typ', 'Driver');
            },
            'label' => 'show.form.driver',
            'required' => false,
            'placeholder' => 'book.form.select.placeholder'
        ))
        ->add('greeter', EntityType::class, array(
            'class' => 'AppBundle:Employee',
            'choice_label' => function ($p) {return $p->getFullName();},
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                ->where('e.type = :typ')
                ->setParameter('typ', 'Greeter');
            },
            'label' => 'show.form.greeter',
            'placeholder' => 'book.form.select.placeholder'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Book'
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
