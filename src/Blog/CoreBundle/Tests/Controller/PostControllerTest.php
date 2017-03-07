<?php

namespace Blog\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class PostControllerTest
 */
class PostControllerTest extends WebTestCase
{
    /**
     * Test
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful(), "The response wasn't successful.");

        $this->assertCount(3, $crawler->filter("h2"), "there should be 3 displayed posts");
    }

}
