<?php

namespace App\Repository;

use App\Entity\SocialesA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SocialesA>
 *
 * @method SocialesA|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialesA|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialesA[]    findAll()
 * @method SocialesA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialesARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialesA::class);
    }

//    /**
//     * @return SocialesA[] Returns an array of SocialesA objects
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

//    public function findOneBySomeField($value): ?SocialesA
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
