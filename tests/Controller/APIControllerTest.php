<?php
namespace App\Tests\Controller;

use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class APIControllerTest extends WebTestCase
{
    private $serializer;
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->serializer = SerializerBuilder::create()->build();
    }

    // return test book fields with unique name
    private function getParams()
    {
        $params = array(
            'name' => uniqid('test_api_book_'),
            'author' => 'Test Author',
            'last_read' => '12.07.2006',
            'downloadable' => false,
        );

        return $params;
    }

    // return apiKey parameter for request() method
    // may return empty array, array with wrong key or array with right key
    private function getKey(?bool $goodKey = null)
    {
        $params = array();
        if (!is_null($goodKey)) {
            $apiKey =  $this->client->getContainer()->getParameter('app.api.key');
            $params['apiKey'] = (!$goodKey) ? 'wrong_key' : $apiKey;
        }

        return $params;
    }

    // print request content (for testing)
    private function printContent()
    {
        $deserialized = $this->getContent();
        var_dump($deserialized);
    }

    // deserialize request content into array
    private function getContent()
    {
        return $this->serializer->deserialize($this->client->getResponse()->getContent(), 'array', 'json');
    }

    public function testCreateBookAnon()
    {
        echo "\nTest `CreateBook` without authorization (API)\n";

        $this->client = $this->createClient(); // create client

        $params = $this->getParams(); // get params 

        $this->client->request('POST', '/api/v1/books/add', [], [], [], $this->serializer->serialize($params, 'json')); // send request without `apiKey` at all

        // $this->printContent();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $apiKey = $this->getKey(false); // get wrong `apiKey`
        $this->client->request('POST', '/api/v1/books/add', $apiKey, [], [], $this->serializer->serialize($params, 'json')); // send request  with wrong `apiKey`

        // $this->printContent();
        $this->assertEquals(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateBook()
    {
        echo "\nTest `CreateBook` with authorization (API)\n";

        $this->client = $this->createClient(); // create client

        $params = $this->getParams(); // get parameters
        $apiKey = $this->getKey(true); // get right `apiKey`

        $this->client->request('POST', '/api/v1/books/add', $apiKey, [], [], $this->serializer->serialize($params, 'json')); // send request
        
        // $this->printContent();
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        
        // get id of created book
        $book_id = $this->getContent()['id'];
        
        // send request to get info of this book
        $this->client->request('GET', '/api/v1/books/'.$book_id.'/info', $apiKey); // send request

        // $this->printContent();
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // check the name of the book
        $book_info = $this->getContent();
        $this->assertEquals($params['name'], $book_info['name']);
        
    }
}
