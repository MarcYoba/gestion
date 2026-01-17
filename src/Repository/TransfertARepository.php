<?php

namespace App\Repository;

use App\Entity\TransfertA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TransfertA>
 *
 * @method TransfertA|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransfertA|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransfertA[]    findAll()
 * @method TransfertA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransfertARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransfertA::class);
    }

//    /**
//     * @return TransfertA[] Returns an array of TransfertA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TransfertA
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
