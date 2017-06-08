<?php

namespace AppBundle\Form;

use AppBundle\Entity\Agent;
use AppBundle\Entity\Airport;
use AppBundle\Entity\Book;
use AppBundle\Entity\Flight;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\InternationalCodes\InternationalCodes;

class AgentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, array(
                'label' => 'Agent Email'
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'Agent Lastname'
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'Agent Firstname'
            ))
        ;

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Agent::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_internationalcodes';
    }

}
