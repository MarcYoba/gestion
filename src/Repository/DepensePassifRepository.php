<?php

namespace App\Repository;

use App\Entity\DepensePassif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepensePassif>
 *
 * @method DepensePassif|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepensePassif|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepensePassif[]    findAll()
 * @method DepensePassif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepensePassifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepensePassif::class);
    }

//    /**
//     * @return DepensePassif[] Returns an array of DepensePassif objects
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

//    public function findOneBySomeField($value): ?DepensePassif
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
