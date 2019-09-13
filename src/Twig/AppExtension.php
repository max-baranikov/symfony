<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

// use Symfony\Contracts\Service\ServiceSubscriberInterface;
// use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
// use Symfony\Component\DependencyInjection\ContainerInterface;
// use App\Twig\ContainerInterface;

use App\Service\FileUploader;

class AppExtension extends AbstractExtension
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath']),
        ];
    }

    public function getUploadedAssetPath(?string $path)
    {
        return $this->fileUploader->getUploadsURL($path);
    }
}
