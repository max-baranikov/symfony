<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Book;
use App\Form\BookType;
/**
 * Book controller.
 * @Route("/api/v1/books", name="api_")
 */
class APIController extends FOSRestController
{
  /**
   * Lists all Books (raw data).
   * @Rest\Get("/")
   *
   * @return Response
   */
  public function getBookAction()
  {
    $repository = $this->getDoctrine()->getRepository(Book::class);
    $books = $repository->findall();

    return $this->handleView($this->view($books));
  }
  
}