<?php
// src/AppBundle/FileUploader.php
namespace AppBundle;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function uploadLogo(UploadedFile $file)
    {
        return $this->processFile($file, $this->targetDir."/logos");
    }

    public function uploadDoc(UploadedFile $file)
    {
        return $this->processFile($file, $this->targetDir."/docs");
    }

    private function processFile(UploadedFile $file, string $dir)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($dir, $fileName);
        return $fileName;
    }
}
