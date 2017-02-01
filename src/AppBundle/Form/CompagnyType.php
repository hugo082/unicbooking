<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CompagnyType extends AbstractType
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
        ->add('portageprice', IntegerType::class, array(
            'label' => 'Portage per bag Price (EUR)',
            'required' => false
        ))
        ->add('color', TextType::class, array(
            'label' => 'Color',
            'attr' => array( 'style' => 'display: inline-block; width:80%')
        ))
        ->add('logo', FileType::class, array(
            'label' => 'Logo (Image file)',
            'data_class' => NULL
        ));

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
        //     $data = $event->getData();
        //     echo "<br><br>PRE_SET_DATA<br>";
        //     var_dump($data);
        //     //var_dump($data->getLogo());
        //     $event->setData($data);
        // });
        //
        // $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event){
        //     $data = $event->getData();
        //     echo "<br><br>POST_SET_DATA<br>";
        //     var_dump($data);
        //     //var_dump($data->getLogo());
        //     $event->setData($data);
        // });
        //
        // $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event){
        //     $data = $event->getData();
        //     echo "<br><br>PRE_SUBMIT<br>";
        //     var_dump($data);
        //     unset($data["logo"]);
        //     var_dump($data);
        //     //var_dump($data->getLogo());
        //     $event->setData($data);
        // });
        //
        // $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event){
        //     $data = $event->getData();
        //     echo "<br><br>SUBMIT<br>";
        //     var_dump($data);
        //     //var_dump($data->getLogo());
        //     $event->setData($data);
        // });
        //
        // $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
        //     $data = $event->getData();
        //     echo "<br><br>POST_SUBMIT<br>";
        //     var_dump($data);
        //     //var_dump($data->getLogo());
        //     $event->setData($data);
        // });
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Compagny'
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'appbundle_compagny';
    }


}
