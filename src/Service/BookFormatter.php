<?php
namespace App\Service;

use App\Entity\Book;
use App\Service\UploadPather;
use Symfony\Component\HttpFoundation\Request;

class BookFormatter extends UploadPather
{
    public function format(Request $request, Book $book)
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $uploadDir = $baseurl . $this->uploadsURL;

        $coverUrl = ((file_exists($this->uploadsDir . $book->getCoverPath())) ? ($uploadDir . $book->getCoverPath()) : null);

        if ($book->getDownloadable() && file_exists($this->booksDir . $book->getFilePath())) {
            $path = $this->router->generate('book_download', ['id' => $book->getId()]);
            $fileUrl = $baseurl . $path;
        } else {
            $fileUrl = null;
        }

        return array(
            'id' => $book->getId(),
            'name' => $book->getName(),
            'author' => $book->getAuthor(),
            'last_read' => $book->getLastRead()->format('d.m.Y'),
            'cover' => $coverUrl,
            'file' => $fileUrl,
        );
    }

    public function formatArray(Request $request, array $books)
    {
        $books_formatted = array();
        foreach ($books as $key => $book) {
            $books_formatted[] = $this->format($request, $book);
        }
        return $books_formatted;
    }
}
