<?php
namespace App\Controller;

use App\Form\APIType;
use App\Service\BookFormatter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Book;
use App\Form\BookType;

/**
 * Book controller.
 * @Route("/api/v1/books", name="api_")
 */
class APIController extends FOSRestController
{
    // /**
    //  * Lists all Books (FOSRest).
    //  * @Rest\Get("/", name="list")
    //  *
    //  * @return Response
    //  */
    // public function getBookAction(Request $request, BookFormatter $bookFormatter)
    // {
    //     $repository = $this->getDoctrine()->getRepository(Book::class);
    //     $books = $repository->findall();

    //     $books_formatted = $bookFormatter->formatArray($request,$books);

    //     return $this->handleView($this->view($books_formatted));
    // }

    /**
     * Lists all Books (JMS/Serializer).
     * @Route("/", name="list", methods={"GET"})
     *
     */
    public function getBooksList(Request $request, BookFormatter $bookFormatter)
    {
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $books = $repository->findall();

        $books_formatted = $bookFormatter->formatArray($request, $books);

        $serializer = $this->get('jms_serializer');
        $books_json = $serializer->serialize($books_formatted, 'json');
        return new JsonResponse($books_json, Response::HTTP_OK, [], true);
    }

    /**
     * Book info by id.
     * @Route("/{id}/info", name="info", methods={"GET"}, requirements={"id"="\d+"})
     *
     */
    public function getBooksInfo(Request $request, Book $book, BookFormatter $bookFormatter)
    {
        $book_formatted = $bookFormatter->format($request, $book);

        $serializer = $this->get('jms_serializer');
        $books_json = $serializer->serialize($book_formatted, 'json');
        return new JsonResponse($books_json, Response::HTTP_OK, [], true);
    }

    /**
     * Create new book (fields: name, author,).
     * @Route("/add", name="add", methods={"GET", "POST"})
     *
     */
    public function addBook(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(APIType::class, $book);
        $data = json_decode($request->getContent(), true);

        unset($data['apiKey']);

        $result = array();
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $book->setCover(false);
            $book->setFile(false);
            $book->setDownloadable(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $result['status'] = 'ok';
            $result['id'] = $book->getId();
            $http_status = Response::HTTP_CREATED;
        } else {
            $result['status'] = 'fail';
            $result['msg'] = $form->getErrors();
            $http_status = Response::HTTP_NOT_FOUND;
        }

        $serializer = $this->get('jms_serializer');
        $result = $serializer->serialize($result, 'json');
        return new JsonResponse($result, $http_status, [], true);
    }

}