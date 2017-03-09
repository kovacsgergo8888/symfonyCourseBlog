<?php

namespace Blog\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Blog\ModelBundle\Repository\AuthorRepository;

class AuthorControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        /** @var AuthorRepository $authorRepository */
        $authorRepository = $client->getContainer()->get('doctrine')->getRepository('ModelBundle:Author');
        $author = $authorRepository->findFirstAuthor();

        $authorPostCount = $author->getPosts()->count();

        $crawler = $client->request('GET', '/author/' . $author->getSlug());

        $this->assertTrue($client->getResponse()->isSuccessful(), 'the response wasn\'t successful');
        $this->assertCount($authorPostCount, $crawler->filter('h2'), "There should be $authorPostCount posts.");
    }

}
