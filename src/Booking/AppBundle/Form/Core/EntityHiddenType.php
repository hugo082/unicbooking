<?php
namespace Booking\AppBundle\Form\Core;

use Symfony\Component\Form\AbstractType;
use Booking\AppBundle\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

class EntityHiddenType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIdTransformer($this->objectManager, $options['class']);
        $builder->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('class')
            ->setDefaults(array(
                'invalid_message' => 'The entity does not exist.'
            ))
        ;
    }

    public function getParent()
    {
        return HiddenType::class;
    }

    public function getName()
    {
        return 'entity_hidden';
    }
}