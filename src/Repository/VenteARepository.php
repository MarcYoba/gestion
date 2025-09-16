<?php

namespace App\Repository;

use App\Entity\VenteA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VenteA>
 *
 * @method VenteA|null find($id, $lockMode = null, $lockVersion = null)
 * @method VenteA|null findOneBy(array $criteria, array $orderBy = null)
 * @method VenteA[]    findAll()
 * @method VenteA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VenteA::class);
    }

//    /**
//     * @return VenteA[] Returns an array of VenteA objects
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

//    public function findOneBySomeField($value): ?VenteA
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findRapportToDay($date) : array {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
    
        return $this->createQueryBuilder('v')
            ->where('v.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        
        ;
    }
}
