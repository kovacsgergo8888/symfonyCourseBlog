<?php

namespace Blog\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        return $this->render("CoreBundle:Post:show.html.twig",[
            "post" => $post
        ]);

    }

}
