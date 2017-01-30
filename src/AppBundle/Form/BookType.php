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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class BookType extends AbstractType
{

    private $user = NULL;
    protected $token;

    public function __construct(TokenStorage $token)
    {
        $this->token = $token;
    }

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
        ->add('airport', EntityType::class, array('class' => 'AppBundle:Airport',
        'placeholder' => 'book.form.select.placeholder',
        'choice_label' => function ($p) {return $p->getFullName();},
        'label' => 'book.form.air',
        'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('a')
            ->where('a.compagny = :cmp')
            ->setParameter('cmp', $this->getUser()->getCompagny())
            ->orwhere('a.compagny is NULL')
            ->andwhere('a.removed = :rm')
            ->setParameter('rm', false );
        }
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
        ->add('product', EntityType::class, array('class' => 'AppBundle:Product',
        'placeholder' => 'book.form.select.placeholder',
        'choice_label' => function ($p) {return $p->getFullName();},
        'choice_attr' => function($p) {return ['trspt' => $p->getTransport()];},
        'label' => 'book.form.prod',
        'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('p')
            ->where('p.compagny = :cmp')
            ->setParameter('cmp', $this->getUser()->getCompagny())
            ->orwhere('p.compagny is NULL')
            ->andwhere('p.removed = :rm')
            ->setParameter('rm', false );
        }))
        ->add('flight', EntityType::class, array( 'class' => 'AppBundle:Flight',
        'choice_label' => function ($f) {return $f->getFullName();},
        'label' => 'book.form.flight',
        'placeholder' => 'book.form.select.placeholder',
        'choice_attr' => function($f) {return ['is' => $f->getType(), 'airp' => $f->getMainAirport()->getId()];},
        'query_builder' => function (EntityRepository $er) {
            $cmp = $this->getUser()->getCompagny();
            return $er->createQueryBuilder('f')
            ->where('f.compagny = :cmp')
            ->setParameter('cmp', $cmp)
            ->orwhere('f.compagny is NULL')
            ->orwhere(':ucmp is NULL')
            ->setParameter('ucmp', $cmp)
            ->andwhere('f.removed = :rm')
            ->setParameter('rm', false );
        }
        ))
        ->add('bags', ChoiceType::class, array(
            'label' => 'book.form.bags',
            'placeholder' => 'book.form.select.placeholder',
            'choices'  => array(
                '0 bag 0€' => 0,
                '1 bag 10€' => 1,
                '2 bags 20€' => 2,
                '3 bags 30€' => 3,
                '4 bags 40€' => 4,
                '5 bags 50€' => 5,
                '6 bags 60€' => 6,
                '7 bags 70€' => 7,
                '8 bags 80€' => 8,
                '9 bags 90€' => 9,
                '10 bags 100€' => 10,
                '11 bags 110€' => 11,
                '12 bags 120€' => 12,
                '13 bags 130€' => 13,
                '14 bags 140€' => 14,
                '15 bags 150€' => 15,
                '16 bags 160€' => 16,
                '17 bags 170€' => 17,
                '18 bags 180€' => 18,
                '19 bags 190€' => 19,
                '20 bags 200€' => 20,
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

            if (!$product || null === $product->getId()) {
                $form
                ->add('customers', CollectionType::class, array(
                    'entry_type'   => CustomerType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'label' => 'book.form.cust'
                ))
                ->add('adultcus', IntegerType::class, array(
                    'label' => 'book.form.adult',
                    'data' => '1'
                ))
                ->add('childcus', IntegerType::class, array(
                    'label' => 'book.form.chilu2y',
                    'data' => '0'
                ));
            } else {
                $form
                ->add('adultcus', IntegerType::class, array(
                    'label' => 'book.form.adult'
                ))
                ->add('childcus', IntegerType::class, array(
                    'label' => 'book.form.chilu2y'
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

    private function getUser()
    {
        if ($this->user == NULL) {
            $this->user = $this->token->getToken()->getUser();
        }
        return $this->user;
    }

}
