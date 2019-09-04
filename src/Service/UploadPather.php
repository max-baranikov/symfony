<?php
namespace App\Service;

class UploadPather
{
    protected $targetDirectory;
    protected $publicPath;

    public function __construct($targetDirectory, $publicPath)
    {
        $this->targetDirectory = $targetDirectory;
        $this->publicPath = $publicPath;
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
