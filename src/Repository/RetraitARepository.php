<?php

namespace App\Repository;

use App\Entity\RetraitA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RetraitA>
 *
 * @method RetraitA|null find($id, $lockMode = null, $lockVersion = null)
 * @method RetraitA|null findOneBy(array $criteria, array $orderBy = null)
 * @method RetraitA[]    findAll()
 * @method RetraitA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetraitARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RetraitA::class);
    }

//    /**
//     * @return RetraitA[] Returns an array of RetraitA objects
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

//    public function findOneBySomeField($value): ?RetraitA
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
