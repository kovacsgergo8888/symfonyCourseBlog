<?php

namespace Blog\AdminBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorControllerTest extends WebTestCase
{
    /**
     *
     */
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW' => 'admin'
        ]);

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/author/');
        $this->assertTrue($client->getResponse()->isSuccessful(), "The response wasn't successful!");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(
            [
                "blog_modelbundle_author[name]" => "Someone"
            ]
        );

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Someone")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Update')->link());

        $form = $crawler->selectButton('Update')->form(
            [
                "blog_modelbundle_author[name]" => "Another one"
            ]
        );

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Another one"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Another one/', $client->getResponse()->getContent());
    }

}
