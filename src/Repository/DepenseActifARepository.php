<?php

namespace App\Repository;

use App\Entity\DepenseActifA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepenseActifA>
 *
 * @method DepenseActifA|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepenseActifA|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepenseActifA[]    findAll()
 * @method DepenseActifA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseActifARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepenseActifA::class);
    }

//    /**
//     * @return DepenseActifA[] Returns an array of DepenseActifA objects
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

//    public function findOneBySomeField($value): ?DepenseActifA
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
