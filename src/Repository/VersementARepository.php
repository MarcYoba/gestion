<?php

namespace App\Repository;

use App\Entity\VersementA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VersementA>
 *
 * @method VersementA|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersementA|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersementA[]    findAll()
 * @method VersementA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersementARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersementA::class);
    }

//    /**
//     * @return VersementA[] Returns an array of VersementA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VersementA
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
