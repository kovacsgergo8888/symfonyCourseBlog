<?php

namespace Blog\CoreBundle\Controller;

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
        $postRepository = $this->getDoctrine()->getRepository('ModelBundle:Post');
        $posts = $postRepository->findAll();
        $latestPosts = $postRepository->findLatest(10);

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
        $post = $this->getDoctrine()->getRepository('ModelBundle:Post')->findOneBy(
            [
                "slug" => $slug
            ]
        );

        if ($post === null) {
            throw $this->createNotFoundException("Post wasn't found!");
        }

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
        $post = $this->getDoctrine()->getRepository('ModelBundle:Post')->findOneBy(["slug" => $slug]);

        if ($post === null) {
            throw $this->createNotFoundException("Post not found.");
        }

        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Comment was submitted');

            return $this->redirectToRoute('blog_core_post_show', ["slug" => $slug]);
        }

        return [];
    }

}
