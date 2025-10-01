<?php

namespace App\Repository;

use App\Entity\DepensePassifA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepensePassifA>
 *
 * @method DepensePassifA|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepensePassifA|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepensePassifA[]    findAll()
 * @method DepensePassifA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepensePassifARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepensePassifA::class);
    }

//    /**
//     * @return DepensePassifA[] Returns an array of DepensePassifA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DepensePassifA
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
