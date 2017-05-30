<?php
namespace AppBundle\Doctrine;

use Doctrine\ORM\Id\AbstractIdGenerator;
use Doctrine\ORM\EntityManager;

class IdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        $entity_name = $em->getClassMetadata(get_class($entity))->getName();
        $max_value = 99999;
        $current = 17000;
        while (true) {
            $item = $em->find($entity_name, $current);
            if (!$item) {
                return $current;
            }

            // Should we stop?
            $current++;
            if ($current > $max_value) {
                throw new \Exception('RandomIdGenerator worked hardly, but failed to generate unique ID :(');
            }
        }
    }
}
