<?php

namespace App\Repository;

use App\Entity\DepenseA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepenseA>
 *
 * @method DepenseA|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepenseA|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepenseA[]    findAll()
 * @method DepenseA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepenseA::class);
    }

//    /**
//     * @return DepenseA[] Returns an array of DepenseA objects
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

//    public function findOneBySomeField($value): ?DepenseA
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
