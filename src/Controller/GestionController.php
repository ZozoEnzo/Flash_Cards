<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class GestionController extends AbstractController
{
    /**
     * @Route("/gestion", name="app_gestion")
     */
    public function index(Request $request, UserInterface $user): Response
    {
        $categories = $user->getCategories();
        return $this->render('gestion/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
