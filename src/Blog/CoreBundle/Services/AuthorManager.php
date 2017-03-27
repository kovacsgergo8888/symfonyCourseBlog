<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2017.03.27.
 * Time: 20:47
 */

namespace Blog\CoreBundle\Services;
use Blog\ModelBundle\Entity\Author;
use Blog\ModelBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AuthorManager
 * @package Blog\CoreBundle\Services
 */
class AuthorManager
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $slug
     * @return Author|null|object
     */
    public function findBySlug($slug)
    {

        $author = $this->em->getRepository('ModelBundle:Author')->findOneBy(
            [
                "slug" => $slug
            ]
        );

        if ($author === null) {
            throw new NotFoundHttpException("Author not found");
        }

        return $author;
    }

    /**
     * @param Author $author
     * @return array|Post[]
     */
    public function findPosts(Author $author)
    {
        $posts = $this->em->getRepository('ModelBundle:Post')->findBy(["author" => $author]);
        return $posts;
    }
}