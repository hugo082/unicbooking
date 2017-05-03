<?php
// src/AppBundle/EventListener/BrochureUploadListener.php
namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

use AppBundle\Entity\Compagny;

class UploadListener
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
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
        $this->uploadLogo($entity);
        $this->uploadDoc($entity);
    }

    private function uploadLogo(Compagny $entity) {
        $file = $entity->getLogo();
        if (!$file instanceof UploadedFile) {
            return;
        }
        $fileName = $this->processFile($file, "/logos");
        $entity->setLogo($fileName);
    }

    private function uploadDoc(Compagny $entity) {
        $file = $entity->getDoc();
        if (!$file instanceof UploadedFile) {
            return;
        }
        $fileName = $this->processFile($file, "/docs");
        $entity->setDoc($fileName);
    }

    private function processFile(UploadedFile $file, $dir)
    {
        $dir = $this->targetDir . $dir;
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($dir, $fileName);
        return $fileName;
    }
}
