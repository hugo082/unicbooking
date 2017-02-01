<?php
// src/AppBundle/EventListener/BrochureUploadListener.php
namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use AppBundle\Entity\Compagny;
use AppBundle\FileUploader;

class LogoUploadListener
{
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    private function uploadFile($entity)
    {
        if (!$entity instanceof Compagny) {
            return;
        }
        $file = $entity->getLogo();
        if (!$file instanceof UploadedFile) {
            return;
        }
        $fileName = $this->uploader->upload($file);
        $entity->setLogo($fileName);
    }
}
