<?php
namespace App\Tests\EventListener;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookSubscriberTest extends WebTestCase
{
    public function testPreRemove()
    {
        echo "\nTest `Remove Book` with BookSubscriber\n";

        // loading necessary stuff
        $container = $this->createClient()->getContainer();
        $doctrine = $container->get('doctrine');
        $repository = $doctrine->getRepository(Book::class);
        
        $targetDir = $container->getParameter('books_dir');   // get server's upload directory

        // find book, which contains the cover and the file (fields in my entity)
        $book = $repository->findOneBy([
            'file' => 1,
            'cover' => 1,
        ]);

        $book_dir = $targetDir . ($book->getBookDir());     // get path for the directory with book files
        
        $this->assertTrue(file_exists($book_dir));      // first check if the directory really exsists

        // remove it
        $entityManager = $doctrine->getManager();
        $entityManager->remove($book);
        $entityManager->flush();
        
        // if everything allright, preRemove should remove this folder
        // so check it
        $this->assertTrue(!file_exists($book_dir));
    }
}
