<?php

namespace App\Repository;

use App\Entity\BalanceA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BalanceA>
 *
 * @method BalanceA|null find($id, $lockMode = null, $lockVersion = null)
 * @method BalanceA|null findOneBy(array $criteria, array $orderBy = null)
 * @method BalanceA[]    findAll()
 * @method BalanceA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BalanceA::class);
    }

//    /**
//     * @return BalanceA[] Returns an array of BalanceA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BalanceA
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findBdyDateAgence($year,$agence) : array 
    {
        return $this->createQueryBuilder('b')
            ->where('YEAR(b.createtAt) =:dates')
            ->andWhere('b.agence =:agences')
            ->setParameter('dates',$year)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        ;   
    }

    public function findByCompteYearAgence($compte,$year,$agence) : ?BalanceA 
    {
        return $this->createQueryBuilder('b')
            ->where('YEAR(b.createtAt) =:dates')
            ->andWhere('b.Compte =:comptes')
            ->andWhere('b.agence =:agences')
            ->setParameter('dates',$year)
            ->setParameter('comptes',$compte)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
