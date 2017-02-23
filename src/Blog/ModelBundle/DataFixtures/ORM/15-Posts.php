<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2017.02.13.
 * Time: 21:15
 */

namespace Blog\ModelBundle\DataFixtures;

use Blog\ModelBundle\Entity\Author;
use Blog\ModelBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class Posts
 * @package Blog\ModelBundle\DataFixtures
 */
class Posts extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 15;
    }


    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $p1 = new Post();
        $p1->setTitle("Lorem ipsum");
        $p1->setBody("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus suscipit condimentum metus fringilla scelerisque. Donec mollis est eu nisi tincidunt, a euismod nibh efficitur. Cras eu pulvinar massa. Praesent pulvinar elit nulla, vitae consectetur libero ornare at. Morbi urna turpis, luctus in velit sed, sodales scelerisque neque. Suspendisse potenti. Nunc posuere ex ut erat pretium scelerisque.");
        $p1->setAuthor($this->getAuthor($manager, 'David'));
        $p1->setTags($this->addTags($manager, ['tag1','tag2']));

        $p2 = new Post();
        $p2->setTitle("Lorem ipsum");
        $p2->setBody("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus suscipit condimentum metus fringilla scelerisque. Donec mollis est eu nisi tincidunt, a euismod nibh efficitur. Cras eu pulvinar massa. Praesent pulvinar elit nulla, vitae consectetur libero ornare at. Morbi urna turpis, luctus in velit sed, sodales scelerisque neque. Suspendisse potenti. Nunc posuere ex ut erat pretium scelerisque.");
        $p2->setAuthor($this->getAuthor($manager, 'Eddie'));

        $p3 = new Post();
        $p3->setTitle("Lorem ipsum");
        $p3->setBody("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus suscipit condimentum metus fringilla scelerisque. Donec mollis est eu nisi tincidunt, a euismod nibh efficitur. Cras eu pulvinar massa. Praesent pulvinar elit nulla, vitae consectetur libero ornare at. Morbi urna turpis, luctus in velit sed, sodales scelerisque neque. Suspendisse potenti. Nunc posuere ex ut erat pretium scelerisque.");
        $p3->setAuthor($this->getAuthor($manager, 'Eddie'));

        $manager->persist($p1);
        $manager->persist($p2);
        $manager->persist($p3);

        $manager->flush();
    }

    /**
     * Get an author
     *
     * @param ObjectManager $objectManager
     * @param string $name
     *
     * @return Author
     */
    private function getAuthor(ObjectManager $objectManager, $name)
    {
        /** @var Author $author */
        $author = $objectManager->getRepository('ModelBundle:Author')->findOneBy(
            [
                "name" => $name,
            ]
        );

        return $author;
    }

    /**
     * @param ObjectManager $objectManager
     * @param array $array
     * @return ArrayCollection
     */
    private function addTags(ObjectManager $objectManager, $array)
    {
        $tags = [];

        foreach ($array as $tag) {
            $tagEntity = $objectManager->getRepository('ModelBundle:Tag')->findOneBy(
                [
                    "description" => $tag
                ]
            );
            $tags[] = $tagEntity;
        }

        return new ArrayCollection($tags);
    }
}