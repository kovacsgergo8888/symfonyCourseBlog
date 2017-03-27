<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2017.03.27.
 * Time: 21:17
 */

namespace Blog\CoreBundle\Services;


use Blog\ModelBundle\Entity\Comment;
use Blog\ModelBundle\Entity\Post;
use Blog\ModelBundle\Form\CommentType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * PostManager constructor.
     * @param EntityManager $entityManager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManager $entityManager, FormFactoryInterface $formFactory)
    {
        $this->em = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @return array|Post[]
     */
    public function findAllPost()
    {
        return $this->em->getRepository("ModelBundle:Post")->findAll();
    }

    /**
     * @param int $num
     * @return array
     */
    public function findLatestPost($num)
    {
        return $this->em->getRepository("ModelBundle:Post")->findLatest($num);
    }

    /**
     * @param $slug
     * @return Post|null|object
     */
    public function findBySlug($slug)
    {
        $post = $this->em->getRepository("ModelBundle:Post")->findOneBy(["slug" => $slug]);

        if ($post === null) {
            throw new NotFoundHttpException("Post was not found!");
        }

        return $post;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return FormInterface|bool
     */
    public function createComment(Post $post, Request $request)
    {
        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->formFactory->create(new CommentType(), $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->em->persist($comment);
            $this->em->flush();

            return true;
        }
        return $form;
    }
}