<?php

namespace App\Repository;

use App\Entity\SalaireA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SalaireA>
 *
 * @method SalaireA|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalaireA|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalaireA[]    findAll()
 * @method SalaireA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaireARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SalaireA::class);
    }

//    /**
//     * @return SalaireA[] Returns an array of SalaireA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SalaireA
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
