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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('airport', ChoiceType::class, array(
            'choices'  => array(
                'book.form.cdg' => 'CDG'
            ),
            'label' => 'book.form.air'
        ))
        ->add('date', DateType::class, array(
            'label' => 'book.form.date',
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],
            'widget' => 'single_text'
        ))
        ->add('service', ChoiceType::class, array(
            'choices'  => array(
                'book.form.dep' => 'DEP',
                'book.form.arr' => 'ARR'
            ),
            'placeholder' => 'book.form.select.placeholder',
            'label' => 'book.form.rqserv'
        ))
        ->add('product', EntityType::class,
        array('class' => 'AppBundle:Product',
        'placeholder' => 'book.form.select.placeholder',
        'choice_label' => function ($p) {return $p->getFullName();},
        'label' => 'book.form.prod'))
        ->add('flight', EntityType::class, array( 'class' => 'AppBundle:Flight',
        'choice_label' => function ($f) {return $f->getFullName();},
        'label' => 'book.form.flight',
        'placeholder' => 'book.form.select.placeholder',
        'choice_attr' => function($f) {return ['is' => $f->getType()];}
        ))
        ->add('bags', IntegerType::class, array(
            'label' => 'book.form.bags'
        ))
        ->add('addresspu', TextType::class, array(
            'label' => "book.form.addpu",
            'required' => false
        ))
        ->add('addressdo', TextType::class, array(
            'label' => "book.form.adddo",
            'required' => false
        ))
        ->add('adultcus', IntegerType::class, array(
            'label' => 'book.form.adult'
        ))
        ->add('childcus', IntegerType::class, array(
            'label' => 'book.form.chilu2y'
        ))
        ->add('customers', CollectionType::class, array(
            'entry_type'   => CustomerType::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'label' => 'book.form.cust'
        ))
        ->add('nameboard', TextareaType::class, array(
            'label' => 'book.form.nameboard',
            'required' => false
        ))
        ->add('note', TextareaType::class, array(
            'label' => 'book.form.note',
            'required' => false
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
