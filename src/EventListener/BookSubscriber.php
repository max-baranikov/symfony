<?php
namespace App\EventListener;

use App\Entity\Book;
use App\Entity\User;
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
            Events::prePersist,
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Book) {
            // $this->fileUploader->remove($entity->getBookDir());
            $this->fileUploader->remove($entity->getCoverPath(),false);
            $this->fileUploader->remove($entity->getFilePath());
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            // Kind of improvement
            $entity->setApiKey(md5($entity->serialize() . time() . 'apiKey'));
        }
    }
}
