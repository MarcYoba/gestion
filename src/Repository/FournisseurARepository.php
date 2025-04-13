<?php

namespace App\Repository;

use App\Entity\FournisseurA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FournisseurA>
 *
 * @method FournisseurA|null find($id, $lockMode = null, $lockVersion = null)
 * @method FournisseurA|null findOneBy(array $criteria, array $orderBy = null)
 * @method FournisseurA[]    findAll()
 * @method FournisseurA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FournisseurA::class);
    }

//    /**
//     * @return FournisseurA[] Returns an array of FournisseurA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FournisseurA
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
