<?php

namespace AppBundle\Form;

use AppBundle\Entity\Airport;
use AppBundle\Entity\Compagny;
use AppBundle\Entity\InternationalCodes\AirportsCodes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

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
        ->add('codes', InternationalCodesType::class, array(
            'data_class' => AirportsCodes::class,
            "precision" => InternationalCodesType::PRECISION_FULL
        ))
        ->add('selectable', CheckboxType::class, array(
            'label' => 'Supported (Possibility of servicing this airport)',
            'required' => false
        ))
        ->add('compagny', EntityType::class, array(
            'class' => 'AppBundle:Compagny',
            'placeholder' => 'book.form.select.placeholder',
            'choice_label' => function (Compagny $p) { return $p->getFullName();},
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
            'data_class' => Airport::class,
            'master_class' => Airport::class
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
