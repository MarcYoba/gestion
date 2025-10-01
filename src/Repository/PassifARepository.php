<?php

namespace App\Repository;

use App\Entity\PassifA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PassifA>
 *
 * @method PassifA|null find($id, $lockMode = null, $lockVersion = null)
 * @method PassifA|null findOneBy(array $criteria, array $orderBy = null)
 * @method PassifA[]    findAll()
 * @method PassifA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PassifARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PassifA::class);
    }

//    /**
//     * @return PassifA[] Returns an array of PassifA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PassifA
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByRefDate($ref, $date) : ?PassifA
    {
        return $this->createQueryBuilder('a')
            ->where('a.REF = :refference')
            ->andWhere('YEAR(a.createdAt) = :date')
            ->setParameter('refference',$ref)
            ->setParameter('date',$date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByYear($anne) : array 
    {
        return $this->createQueryBuilder('a')
            ->andWhere('YEAR(a.createdAt) = :date')
            ->setParameter('date',$anne)
            ->getQuery()
            ->getResult()
        ;
    }
}
