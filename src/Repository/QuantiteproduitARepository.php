<?php

namespace App\Repository;

use App\Entity\QuantiteproduitA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuantiteproduitA>
 *
 * @method QuantiteproduitA|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantiteproduitA|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantiteproduitA[]    findAll()
 * @method QuantiteproduitA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantiteproduitARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantiteproduitA::class);
    }

//    /**
//     * @return QuantiteproduitA[] Returns an array of QuantiteproduitA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuantiteproduitA
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
