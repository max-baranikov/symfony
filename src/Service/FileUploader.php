<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $publicPath;

    public function __construct($targetDirectory, $publicPath)
    {
        $this->targetDirectory = $targetDirectory;
        $this->publicPath = $publicPath;
    }

    public function upload(UploadedFile $file, string $path = '', ?string $fileName = null)
    {
        // avoid bad charcters and ununique filenames
        if (is_null($fileName)) {

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        }
        if ($path[0] != '/') {
            $path = '/' . $path;
        }

        try {
            // not so good, because there will be a lot of files in just one folder
            // guess will be better to split it up by id range [1-20], [21-40], etc.
            $file->move($this->getTargetDirectory() . $path, $fileName);
        } catch (FileException $e) {
            return false;
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function remove($filepath)
    {
        $full_path = $this->getTargetDirectory() . '/' . $filepath;

        if (is_file($full_path)) {
            if (file_exists($full_path)) {
                try {
                    unlink($full_path);
                    return true;
                } catch (Exception $th) {
                    //throw $th;
                    return false;
                }
            }
        } else {
            //Get a list of all of the file names in the folder.
            $files = glob($full_path . '/*');
            try {

                //Loop through the file list.
                foreach ($files as $file) {
                    //Make sure that this is a file and not a directory.
                    if (is_file($file)) {
                        //Use the unlink function to delete the file.
                        unlink($file);
                    }
                }
                // and finally delete the directory
                rmdir($full_path);
                return true;
            } catch (Exception $th) {
                return false;
            }
        }
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function getPublicPath(?string $path)
    {
        $publicPath = $this->publicPath;
        if (!is_null($path)) {
            if ($path[0] != '/') {
                $publicPath .= '/';
            }

            $publicPath .= $path;
        }

        return $publicPath;
    }

}
