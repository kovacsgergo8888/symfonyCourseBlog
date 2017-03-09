<?php

namespace Blog\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AuthorController extends Controller
{
    /**
     * @Route("/author/{slug}")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $author = $this->getDoctrine()->getRepository('ModelBundle:Author')->findOneBy(
            [
                "slug" => $slug
            ]
        );

        if ($author === null) {
            throw $this->createNotFoundException("Author not found");
        }

        $posts = $this->getDoctrine()->getRepository('ModelBundle:Post')->findBy(
            [
                "author" => $author
            ]
        );

        return $this->render('CoreBundle:Author:show.html.twig', array(
            "author" => $author,
            "posts" => $posts,
        ));
    }

}
