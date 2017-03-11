<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2017.03.11.
 * Time: 15:36
 */

namespace Blog\ModelBundle\DataFixtures;

use Blog\ModelBundle\Entity\Comment;
use Blog\ModelBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class Comments extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $c1 = new Comment();
        $c1->setAuthorName("asdf");
        $c1->setBody("asdf");
        $c1->setPost($this->getPost($manager, 'Lorem ipsum'));

        $c2 = new Comment();
        $c2->setAuthorName("sdfsdf");
        $c2->setBody("sdfsdf");
        $c2->setPost($this->getPost($manager, 'Lorem ipsum3'));

        $c3 = new Comment();
        $c3->setAuthorName("qweqwe");
        $c3->setBody("qweqwe");
        $c3->setPost($this->getPost($manager, 'Lorem ipsum3'));

        $manager->persist($c1);
        $manager->persist($c2);
        $manager->persist($c3);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }

    private function getPost(ObjectManager $manager, $title)
    {
        return $manager->getRepository('ModelBundle:Post')->findOneBy(["title" => $title]);
    }
}
