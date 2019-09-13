<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use App\Entity\Book;
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
            new TwigFunction('img', [$this, 'getBookImage'], array(
                'is_safe' => array('html'),
                'needs_environment' => true,
            )),
        ];
    }

    public function getBookImage(\Twig_Environment $environment, Book $book)
    {
        $renderArray = array(
            'book' => $book,
        );
        return $environment->render('book/_img.html.twig', $renderArray);
    }

    public function getUploadedAssetPath(?string $path)
    {
        return $this->fileUploader->getUploadsURL($path);
    }
}
