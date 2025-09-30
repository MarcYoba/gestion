<?php

namespace App\Repository;

use App\Entity\DepenseActif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepenseActif>
 *
 * @method DepenseActif|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepenseActif|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepenseActif[]    findAll()
 * @method DepenseActif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseActifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepenseActif::class);
    }

//    /**
//     * @return DepenseActif[] Returns an array of DepenseActif objects
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

//    public function findOneBySomeField($value): ?DepenseActif
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
