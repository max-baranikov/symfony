<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BookControllerTest extends WebTestCase
{
    public function testCreateBookAnon()
    {
        echo "\nTest `CreateBook` without authorization\n";

        $client = static::createClient(); // create unauthorized client
        $client->followRedirects(true); // allow auto following redirects

        $crawler = $client->request('GET', '/books');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // check that button `Create new` isn't rendered for unauthorized user
        $this->assertEquals(
            0,
            $crawler->filter('html a.book__new')->count()
        );

        $crawler = $client->request('GET', '/books/add'); // trying to load the page anyway

        // assuring that we was redirected to login form
        $this->assertGreaterThan(
            0,
            $crawler->filter('html form[action="/login_check"]')->count()
        );

    }

    public function testCreateBook()
    {
        echo "\nTest `CreateBook` with authorization\n";

        // create authorized client
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'user',
        ]);

        $path = $client->getContainer()->getParameter('files_dir');     // get path of files directory

        $client->followRedirects(true); // allow auto following redirects
        $crawler = $client->request('GET', '/books');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // check that btn `Create new` is rendered
        $this->assertGreaterThan(
            0,
            $crawler->filter('html a.book__new')->count()
        );

        $crawler = $client->clickLink('Create new'); // follow link

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // load form
        $form = $crawler->selectButton('Save')->form();

        // create unique name
        $name = uniqid('test_book_');

        // fill form values
        $form['book[name]'] = $name;
        $form['book[author]'] = 'Test Author';
        $form['book[downloadable]']->tick(); // set true to the checkbox

        $form['book[last_read][month]']->select(6);
        $form['book[last_read][day]']->select(5);
        $form['book[last_read][year]']->select(2018);

        $form['book[cover_filename]']->upload($path . '\test\test.png');
        $form['book[book_filename]']->upload($path . '\test\book.pdf');

        // submit the form
        $crawler = $client->submit($form);

        // echo $client->getResponse()->getContent();
        
        
        // check that it's in the list
        $this->assertGreaterThan(
            0,
            $crawler->filter('html tr td:contains("'.$name.'")')->count()
        );        
        // $this->assertSelectorTextContains('html tr td', $name);  // unfortunately this one doesnt work for me

        
        // find new book on the page
        $crawler = $crawler->filter('html tr td:contains("'.$name.'")')->parents();

        // check that its downloadable
        $this->assertGreaterThan(
            0,
            $crawler->filter('td a:contains("download")')->count()
        );        
    }
}
