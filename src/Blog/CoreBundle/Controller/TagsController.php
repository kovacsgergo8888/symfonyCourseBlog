<?php

namespace Blog\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TagsController extends Controller
{
    /**
     * @Route("/tags/list")
     */
    public function listAction()
    {
        $tags = $this->getDoctrine()->getRepository('ModelBundle:Tag')->findTagsWithPost();

        return $this->render('CoreBundle:Tags:list.html.twig', array(
            "tags" => $tags,
        ));
    }

}
