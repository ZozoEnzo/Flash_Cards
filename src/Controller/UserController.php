<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', ['user' => $this->getUser()]);
    }


    /**
     * @Route("/user/update", name="app_user_update")
     */
    public function updateUser(): Response
    {
        dd('ok');
        return $this->render('security/profil.html.twig', ['user' => $this->getUser()]);
    }
}
