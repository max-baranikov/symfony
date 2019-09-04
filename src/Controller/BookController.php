<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/books", name="book_")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="new", methods={"GET","POST"})
     */
    function new (Request $request, FileUploader $fileUploader): Response {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $bookFile */
            $bookFile = $form['book_filename']->getData();
            if ($bookFile) {
                    $book->setFile(true);
            } else {
                $book->setFile(false);
            }

            /** @var UploadedFile $coverFile */
            $coverFile = $form['cover_filename']->getData();
            if ($coverFile) {
                    $book->setCover(true);
            } else {
                $book->setCover(false);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            // save files on the server after Doctrine generate the Id
            // save book file as book.pdf to /uploads/0-10/1/book.pdf
            if ($bookFile) {
                $bookFileName = $fileUploader->upload($bookFile, $book->getBookDir(), Book::BOOK_FILE);
            }

            // save book cover file as cover to /uploads/0-10/1/cover
            if ($coverFile) {
                $coverFileName = $fileUploader->upload($coverFile, $book->getBookDir(), Book::BOOK_COVER);
            }

            return $this->redirectToRoute('book_index');
        }
        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Book $book, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $bookFile */
            $bookFile = $form['book_filename']->getData();
            if ($bookFile) {
                // ...
                // if ($book->getFile()) {
                //     $old_file = $book->getFilePath();
                //     // delete old file if its exists
                //     $fileUploader->remove($old_file);
                // }
                // upload and set the new one
                $bookFileName = $fileUploader->upload($bookFile, $book->getBookDir(), Book::BOOK_FILE);
                $book->setFile(true);
            }

            /** @var UploadedFile $coverFile */
            $coverFile = $form['cover_filename']->getData();
            if ($coverFile) {
                // ...
                // if ($book->getFile()) {
                //     $old_file = $book->getCoverPath();
                //     // delete old file if its exists
                //     $fileUploader->remove($old_file);
                // }
                // upload and set the new one
                $coverFileName = $fileUploader->upload($coverFile, $book->getBookDir(), Book::BOOK_COVER);
                $book->setCover(true);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete' . $book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }

    /**
     * @Route("/download/{id}", name="download", methods={"GET","POST"})
     */
    public function download(Request $request, Book $book): Response
    {
        return $this->getBookFile($book);
    }

    /**
     * @Route("/view/{id}", name="view", methods={"GET","POST"})
     */
    public function view(Request $request, Book $book): Response
    {
        return $this->getBookFile($book, false);
    }

    private function getBookFile(Book $book, bool $download = true)
    {
        if ($book->getDownloadable()) {
            $upload_path = $this->getParameter('books_directory');
            $book_path = $upload_path . $book->getFilePath();

            if (file_exists($book_path)) {
                $file = new File($book_path);
                $filename = $book->getName() . ' ' . $book->getAuthor() . '.pdf';
                if ($download) {
                    return $this->file($file, $filename);
                }

                return $this->file($file, $filename, ResponseHeaderBag::DISPOSITION_INLINE);
            }
            throw $this->createNotFoundException('The book is not found');

        }
        throw $this->createNotFoundException('The book is not downloadable');
    }
}
