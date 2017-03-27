<?php

namespace Blog\ModelBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient([], [
            "PHP_AUTH_USER" => "superadmin",
            "PHP_AUTH_PW" => "superadmin"
        ]);

        // Create a new entry in the database
        $crawler = $client->request('GET', '/admin/post/');
        $this->assertTrue($client->getResponse()->isSuccessful(), "Response wasn't successful!");
        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        $authorValue = $crawler->filter('#blog_modelbundle_post_author option:contains("David")')->attr("value");

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(
            [
                "blog_modelbundle_post[title]" => "new post",
                "blog_modelbundle_post[body]" => "this is a new post",
                "blog_modelbundle_post[author]" => $authorValue,
            ]
        );

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("new post")')->count(), 'new post is not showing up');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Update')->link());

        $form = $crawler->selectButton('Update')->form(
            [
                "blog_modelbundle_post[body]" => "valami asd Foo",
                "blog_modelbundle_post[title]" => "valami",
                "blog_modelbundle_post[author]" => "Valaki",
            ]
        );

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }
}
