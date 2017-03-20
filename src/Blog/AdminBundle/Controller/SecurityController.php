<?php
/**
 * Created by PhpStorm.
 * User: kovacsgergely
 * Date: 2017.03.20.
 * Time: 19:26
 */

namespace Blog\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;


class SecurityController extends Controller
{
    /**
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/login")
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            "AdminBundle:Security:login.html.twig",
            [
                "last_username" => $session->get(SecurityContext::LAST_USERNAME),
                "error" => $error
            ]
        );
    }

    /**
     * @Route("login_check")
     */
    public function loginCheckAction()
    {

    }
}