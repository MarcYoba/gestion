<?php

namespace App\Repository;

use App\Entity\FactureA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactureA>
 *
 * @method FactureA|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureA|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureA[]    findAll()
 * @method FactureA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureA::class);
    }

//    /**
//     * @return FactureA[] Returns an array of FactureA objects
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

//    public function findOneBySomeField($value): ?FactureA
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
