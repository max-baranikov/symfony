<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        // avoid bad charcters and ununique filenames
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            // not so good, because there will be a lot of files in just one folder
            // guess will be better to split it up by id range [1-20], [21-40], etc.
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function remove($filename)
    {
        if (file_exists($this->getTargetDirectory() . '/' . $filename)) {
            unlink($this->getTargetDirectory() . '/' . $filename);
        }
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
