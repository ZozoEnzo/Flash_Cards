<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\CheckUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, CheckUserService $checkUserService): Response
    {
        $user = new User();
        $error = '';
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($checkUserService->checkUser($form->get('uuid')->getData())){
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
            } else {
                $error = 'Un utilisateur existe déjà avec cet identifiant';
            }
        }
        $form = $this->createForm(RegisterType::class);
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'erreur' => $error
        ]);
    }
}
