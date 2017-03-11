<?php

namespace Blog\CoreBundle\Tests\Controller;

use Blog\ModelBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostControllerTest
 */
class PostControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), "The response wasn't successful.");

        $this->assertCount(3, $crawler->filter("h2"), "there should be 3 displayed posts");
    }

    /**
     * @test
     */
    public function testShow()
    {
        $client = static::createClient();

        /** @var Post $post */
        $post = $client->getContainer()->get('doctrine')->getManager()->getRepository('ModelBundle:Post')->getFirst();
        $crawler = $client->request('get', '/' . $post->getSlug());

        $this->assertTrue($client->getResponse()->isSuccessful(), 'the response was not successful');
        $this->assertEquals($post->getTitle(), $crawler->filter('h1')->text(), 'invalid post title');
        $this->assertGreaterThanOrEqual(1, $crawler->filter('article.comment')->count(), 'There should be at least one comment');
    }

    public function testCreateComment()
    {
        $client = static::createClient();

        /** @var Post $post */
        $post = $client->getContainer()->get('doctrine')->getManager()->getRepository('ModelBundle:Post')->getFirst();
        $crawler = $client->request('get', '/' . $post->getSlug());

        $buttonCrawlerNode = $crawler->selectButton("Send");

        $form = $buttonCrawlerNode->form([
            'blog_modelbundle_comment[authorName]' => 'A humble commenter',
            'blog_modelbundle_comment[body]' => 'oirjgosijgoisejfoisej',
        ]);

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect("/" . $post->getSlug()), "There was no redirection after submit the form.");

        $crawler = $client->followRedirect();

        $this->assertCount(
            1,
            $crawler->filter('html:contains("submitted successfully")')
        );
    }

}
