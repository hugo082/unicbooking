<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\CustomerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
        ->add('agentemail', EmailType::class, array(
            'label' => "agent.email"
        ))
        ->add('agentlastname', TextType::class, array(
            'label' => "agent.lname"
        ))
        ->add('agentfirstname', TextType::class, array(
            'label' => "agent.fname"
        ))
        ->add('airport', ChoiceType::class, array(
            'choices'  => array(
                'book.form.cdg' => 'CDG 1'
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
        ->add('bags', ChoiceType::class, array(
            'label' => 'book.form.bags',
            'placeholder' => 'book.form.select.placeholder',
            'choices'  => array(
                '0 bag 0€' => 0,
                '1 bag 10€' => 1,
                '2 bag 20€' => 2,
                '3 bag 30€' => 3,
                '4 bag 40€' => 4,
                '5 bag 50€' => 5,
                '6 bag 60€' => 6,
                '7 bag 70€' => 7,
                '8 bag 80€' => 8,
                '9 bag 90€' => 9,
                '10 bag 100€' => 10,
                '11 bag 110€' => 11,
                '12 bag 120€' => 12,
                '13 bag 130€' => 13,
                '14 bag 140€' => 14,
                '15 bag 150€' => 15,
                '16 bag 160€' => 16,
                '17 bag 170€' => 17,
                '18 bag 180€' => 18,
                '19 bag 190€' => 19,
                '20 bag 200€' => 20,
            )
        ))
        ->add('timepu', TimeType::class, array(
            'label' => "book.form.timepu",
            'required' => false,
            'widget' => 'single_text',
            'input' => 'string'
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
            'label' => 'book.form.adult',
            'data' => '1'
        ))
        ->add('childcus', IntegerType::class, array(
            'label' => 'book.form.chilu2y',
            'data' => '0'
        ))
        ->add('nameboard', TextareaType::class, array(
            'label' => 'book.form.nameboard',
            'required' => false
        ))
        ->add('note', TextareaType::class, array(
            'label' => 'book.form.note',
            'required' => false
        ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $product = $event->getData();
            $form = $event->getForm();

            // check if the Product object is "new"
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "Product"
            if (!$product || null === $product->getId()) {
                $form
                ->add('customers', CollectionType::class, array(
                    'entry_type'   => CustomerType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'label' => 'book.form.cust'
                ));
            }
        });
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
