<?php

namespace Blog\CoreBundle\Controller;

use Blog\CoreBundle\Services\AuthorManager;
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
        $author = $this->getAuthorManager()->findBySlug($slug);

        if ($author === null) {
            throw $this->createNotFoundException("Author not found");
        }

        $posts = $this->getAuthorManager()->findPosts($author);

        return $this->render('CoreBundle:Author:show.html.twig', array(
            "author" => $author,
            "posts" => $posts,
        ));
    }

    /**
     * @return AuthorManager|object
     */
    private function getAuthorManager()
    {
        return $this->get('author_manager');
    }
}
