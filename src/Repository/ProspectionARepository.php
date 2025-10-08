<?php

namespace App\Repository;

use App\Entity\ProspectionA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProspectionA>
 *
 * @method ProspectionA|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProspectionA|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProspectionA[]    findAll()
 * @method ProspectionA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProspectionARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProspectionA::class);
    }

//    /**
//     * @return ProspectionA[] Returns an array of ProspectionA objects
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

//    public function findOneBySomeField($value): ?ProspectionA
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
