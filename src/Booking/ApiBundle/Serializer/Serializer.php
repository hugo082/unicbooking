<?php
namespace Booking\ApiBundle\Serializer;

use Booking\ApiBundle\Exception\ApiException;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\DependencyInjection\Container;

abstract class Serializer {

    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function serialize($data): ?array
    {
        if ($data instanceof PersistentCollection)
            $data = $data->getValues();
        if (is_array($data)) {
            $res = [];
            foreach ($data as $object)
                $res[] = $this->serialize($object);
            return $res;
        }
        return null;
    }

    protected function subSerialize($data, $service) {
        $serializer = $this->container->get($service);
        if (!$serializer instanceof Serializer)
            throw new ApiException("Serialization", "iSerializer attempt " . get_class($serializer) . " given", 301);
        return $serializer->serialize($data);
    }

    protected function date(?\DateTime $date)
    {
        if ($date)
            return $date->getTimestamp();
        return null;
    }
}