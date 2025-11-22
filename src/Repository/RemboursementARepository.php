<?php

namespace App\Repository;

use App\Entity\RemboursementA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RemboursementA>
 *
 * @method RemboursementA|null find($id, $lockMode = null, $lockVersion = null)
 * @method RemboursementA|null findOneBy(array $criteria, array $orderBy = null)
 * @method RemboursementA[]    findAll()
 * @method RemboursementA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemboursementARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RemboursementA::class);
    }

//    /**
//     * @return RemboursementA[] Returns an array of RemboursementA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RemboursementA
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
