<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\FlashCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FlashCard>
 *
 * @method FlashCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method FlashCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method FlashCard[]    findAll()
 * @method FlashCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FlashCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FlashCard::class);
    }

    public function add(FlashCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FlashCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCardByCategory(Category $category)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.category = :cat')
            ->setParameter('cat', $category)
            ->orderBy('f.Error/f.Successfull*100', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllSortedBySuccessRate()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT f FROM App\Entity\FlashCard f ORDER BY (f.Successfull / (f.Successfull + f.Error)) DESC'
        );
        return $query->getResult();
    }

    public function findByCategoryIdSortedBySuccessRate(Category $category)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT f FROM App\Entity\FlashCard f WHERE f.category = :category ORDER BY (f.successfull / (f.successfull + f.error)) DESC'
        )->setParameter('category', $category);

        return $query->getResult();
    }

//    /**
//     * @return FlashCard[] Returns an array of FlashCard objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FlashCard
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
