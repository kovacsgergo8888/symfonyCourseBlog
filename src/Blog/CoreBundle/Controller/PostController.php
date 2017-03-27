<?php

namespace Blog\CoreBundle\Controller;

use Blog\CoreBundle\Services\PostManager;
use Blog\ModelBundle\Entity\Comment;
use Blog\ModelBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 */
class PostController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $posts = $this->getPostManager()->findAllPost();
        $latestPosts = $this->getPostManager()->findLatestPost(10);

        return $this->render('CoreBundle:Post:index.html.twig', [
            "posts" => $posts,
            "latestPosts" => $latestPosts,
        ]);
    }

    /**
     *
     * @param string $slug
     *
     * @Route("/{slug}")
     * @return Response
     */
    public function showAction($slug)
    {
        $post = $this->getPostManager()->findBySlug($slug);

        $form = $this->createForm(new CommentType());

        return $this->render("CoreBundle:Post:show.html.twig",[
            "post" => $post,
            "form" => $form->createView(),
        ]);

    }

    /**
     * @param Request $request
     * @param $slug
     *
     * @return array|RedirectResponse
     *
     * @Route("/{slug}/create-comment")
     * @Method("POST")
     * @Template("CoreBundle:Post:show.html.twig")
     */
    public function createCommentAction(Request $request, $slug)
    {
        $post = $this->getPostManager()->findBySlug($slug);
        $form = $this->getPostManager()->createComment($post, $request);

        if ($form === true) {
            $this->get('session')->getFlashBag()->add('success', 'Comment was submitted');

            return $this->redirectToRoute('blog_core_post_show', ["slug" => $slug]);
        }

        return [];
    }

    /**
     * @return PostManager|object
     */
    public function getPostManager()
    {
        return $this->container->get("post_manager");
    }

}
