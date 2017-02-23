<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2017.02.23.
 * Time: 22:04
 */

namespace Blog\ModelBundle\DataFixtures;

use Blog\ModelBundle\Entity\Tag;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class Tags extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 11;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $t1 = new Tag();
        $t2 = new Tag();
        $t3 = new Tag();

        $t1->setDescription('tag1');
        $t2->setDescription('tag2');
        $t3->setDescription('tag3');

        $manager->persist($t1);
        $manager->persist($t2);
        $manager->persist($t3);

        $manager->flush();
    }
}