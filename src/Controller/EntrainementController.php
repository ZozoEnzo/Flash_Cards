<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\FlashCardRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class EntrainementController extends AbstractController
{
    /** Entrainement global
     * @Route("/entrainement", name="app_entrainement")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->getFullTrain();
        return $this->render('entrainement/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /** Entrainement toutes les catégories d'une personne
     * @Route("/entrainementComplet", name="app_user_entrainement")
     */
    public function userTrainingFull(): Response
    {
        $cards = [];
        foreach ($this->getUser()->getCategories() as $category)
        {
            foreach ($category->getListCards() as $card)
            {
                array_push($cards,$card);
            }
        }

        return $this->render('entrainement/fullUser.html.twig', [
            'cards' => $cards,
        ]);
    }

    /** Entrainement d'une catégorie spécifique
     * @Route("/entrainement/{id}", name="app_entrainement_category")
     */
    public function entrainementCategory(int $id, CategoryRepository $categoryRepository, FlashCardRepository $flashCardRepository): Response
    {
        $this->getUser();
        $category = $categoryRepository->find($id);
        $cards = $flashCardRepository->findCardByCategory($category);
        if (!$category->isPublic() && $category->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        } else {
            return $this->render('entrainement/trainingCategory.html.twig', [
                'cards' => $cards
            ]);
        }
    }

    /** Entrainement d'une catégorie spécifique
     * @Route("/successCard/{id}", name="app_card_success")
     */
    public function cardSuccess(int $id, FlashCardRepository $flashCardRepository, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $flashCard = $flashCardRepository->find($id);
        if ($flashCard->getCategory()->getUser() === $user) {
            $flashCard->setSuccess(true);
            $flashCard->setSuccessfull($flashCard->getSuccessfull() + 1);
            $entityManager->flush();
            return $this->json(['code' => 200, 'message' => 'Bien joué']);
        } elseif ($flashCard->getCategory()->isPublic()) {
            return $this->json(['code' => 200, 'message' => 'Bien joué']);
        }
        return $this->json(['code' => 403, 'message' => 'Problème au niveau de la carte : ' . $flashCard->getId()], 403);
    }

    /** Entrainement d'une catégorie spécifique
     * @Route("/errorCard/{id}", name="app_card_error")
     */
    public function cardError(int $id, FlashCardRepository $flashCardRepository, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $flashCard = $flashCardRepository->find($id);
        if ($flashCard->getCategory()->getUser() === $user) {
            $flashCard->setSuccess(false);
            $flashCard->setError($flashCard->getError() + 1);
            $entityManager->flush();
            return $this->json(['code' => 200, 'message' => 'Dommage']);
        } elseif ($flashCard->getCategory()->isPublic()) {
            return $this->json(['code' => 200, 'message' => 'Dommage']);
        }
        return $this->json(['code' => 403, 'message' => 'Problème au niveau de la carte : ' . $flashCard->getId()], 403);
    }
}
