<?php

namespace App\Repository;

use App\Entity\InventaireCaisseA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InventaireCaisseA>
 *
 * @method InventaireCaisseA|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventaireCaisseA|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventaireCaisseA[]    findAll()
 * @method InventaireCaisseA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventaireCaisseARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventaireCaisseA::class);
    }

//    /**
//     * @return InventaireCaisseA[] Returns an array of InventaireCaisseA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InventaireCaisseA
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
