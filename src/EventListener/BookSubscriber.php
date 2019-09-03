<?php
namespace App\EventListener;

use App\Entity\Book;
use App\Service\FileUploader;
use Doctrine\Common\EventSubscriber;
// for Doctrine < 2.4: use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class BookSubscriber implements EventSubscriber
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::preRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Book) {
            $entityManager = $args->getObjectManager();
            $this->fileUploader->remove($entity->getBookDir());
            // $this->fileUploader->remove($entity->getBookDir() . '/cover');
            // $this->fileUploader->remove($entity->getBookDir() . '/book.pdf');
        }
    }
}
