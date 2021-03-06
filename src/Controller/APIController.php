<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\APIType;
use App\Service\BookFormatter;
use JMS\Serializer\SerializerInterface;
use FOS\UserBundle\Model\UserManagerInterface as UserManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface as EncoderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Book controller.
 * @Route("/api/v1/books", name="api_")
 */
class APIController extends AbstractController
{
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

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

        $books_json = $this->serializer->serialize($books_formatted, 'json');
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

        $books_json = $this->serializer->serialize($book_formatted, 'json');
        return new JsonResponse($books_json, Response::HTTP_OK, [], true);
    }

    /**
     * Create new book (fields: name, author, last_read).
     * @Route("/add", name="add", methods={"GET", "POST"})
     *
     */
    public function addBook(Request $request)
    {
        $result = array();
        $book = new Book();

        $form = $this->createForm(APIType::class, $book);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
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

        $result = $this->serializer->serialize($result, 'json');
        return new JsonResponse($result, $http_status, [], true);
    }

    /**
     * Edit book.
     * @Route("/{id}/edit", name="edit", methods={"PUT"}, requirements={"id"="\d+"})
     *
     */
    public function editBook(Request $request, Book $book)
    {
        $result = array();
        $form = $this->createForm(APIType::class, $book);
        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            // update entity
            $this->getDoctrine()->getManager()->flush();

            $result['status'] = 'ok';
            $http_status = Response::HTTP_ACCEPTED;
        } else {
            $result['status'] = 'fail';
            $result['msg'] = $form->getErrors();

            $http_status = Response::HTTP_NOT_FOUND;
        }

        $result = $this->serializer->serialize($result, 'json');
        return new JsonResponse($result, $http_status, [], true);
    }

    /**
     * Get credentials.
     * @Route("/token", name="token", methods={"GET"})
     *
     */
    public function getToken(Request $request, EncoderFactory $factory, UserManager $user_manager)
    {
        $result = array();
        $_username = $request->get('username');
        $_password = $request->get('password');

        // Retrieve the user by its username:
        $user = $user_manager->findUserByUsername($_username);

        // Check if the user exists !
        if (!$user) {
            $result['message'] = 'Username doesnt exists';
            $http_status = Response::HTTP_UNAUTHORIZED;
        } else {

            /// Start verification
            $encoder = $factory->getEncoder($user);
            $salt = $user->getSalt();

            if (!$encoder->isPasswordValid($user->getPassword(), $_password, $salt)) {
                $result['message'] = 'Username or Password not valid.';
                $http_status = Response::HTTP_UNAUTHORIZED;
            } else {
                /// End Verification
                // The password matches
                $result['apiKey'] = $user->getApiKey();
                $http_status = Response::HTTP_OK;
            }
        }

        $result = $this->serializer->serialize($result, 'json');
        return new JsonResponse($result, $http_status, [], true);
    }
}
