<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\FlashCard;
use App\Form\AddCategoryType;
use App\Form\AddFlashCardType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class FlashCardController extends AbstractController
{
    /**
     * @Route("/flashcard", name="app_flash_card")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        // route pour afficher toutes les catégory des flash card et avoir la possibilité d'en ajouté, d'en supprimer et la gestion
        $categories = $categoryRepository->findAll();
        return $this->render('flash_card/index.html.twig', [
            'controller_name' => 'FlashCardController',
            'categories' => $categories
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/flashcard/category/add", name="app_category_add")
     */
    public function addCategory(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $category = new Category();
        $form = $this->createForm(AddCategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUser($user);
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_flash_card_add', ['id' => $category->getId()]);
        }

        return $this->render('flash_card/addCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/flashcard/category/delete/{id}", name="app_category_delete")
     */
    public function deleteCategory($id, EntityManagerInterface $entityManager, UserInterface $user, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if($category->getUser() ===  $user || in_array('SUPER_ADMIN',$user->getRoles())) {
            $entityManager->remove($category);
            $entityManager->flush();
            return $this->json(['code'=> 200, 'message' => 'La catégorie a bien été supprimé'],200);
        }
        return $this->json(['code'=> 403, 'message' => 'La catégorie n\'a pas pu être supprimé'], 403);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/flashcard/add/{id}", name="app_flash_card_add")
     */
    public function addFlashCard($id, Request $request, EntityManagerInterface $entityManager, UserInterface $user, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if ($category->getUser() !== $user) {
            return $this->redirectToRoute('app_flash_card');

        }
        $flashCard = new FlashCard();
        $form = $this->createForm(AddFlashCardType::class, $flashCard);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $flashCard->setCategory($category);
            $flashCard->setError(0);
            $flashCard->setSuccessfull(0);
            $flashCard->setSuccess(0);
            $entityManager->persist($flashCard);
            $entityManager->flush();

            return $this->redirectToRoute('app_flash_card_add', ['id' => $category->getId()]);
        }

        return $this->render('flash_card/addFlashCard.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
