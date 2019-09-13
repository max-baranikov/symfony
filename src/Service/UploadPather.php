<?php
namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UploadPather
{
    protected $booksDir;
    protected $uploadsURL;
    protected $uploadsDir;
    protected $router;

    public function __construct($booksDir, $uploadsDir, $uploadsURL, UrlGeneratorInterface $router)
    {
        $this->booksDir = $booksDir;
        $this->uploadsDir = $uploadsDir;
        $this->uploadsURL = $uploadsURL;
        $this->router = $router;
    }

    public function getBooksDir(?string $path = null)
    {
        $booksDir = $this->booksDir;
        if (!is_null($path)) {
            if ($path[0] != '/') {
                $booksDir .= '/';
            }

            $booksDir .= $path;
        }
        return $booksDir;

    }
    public function getUploadsDir(?string $path = null)
    {
        $uploadsDir = $this->uploadsDir;
        if (!is_null($path)) {
            if ($path[0] != '/') {
                $uploadsDir .= '/';
            }

            $uploadsDir .= $path;
        }
        return $uploadsDir;

    }

    public function getUploadsURL(?string $path = null)
    {
        $uploadsURL = $this->uploadsURL;
        if (!is_null($path)) {
            if ($path[0] != '/') {
                $uploadsURL .= '/';
            }

            $uploadsURL .= $path;
        }
        return $uploadsURL;
    }

}
