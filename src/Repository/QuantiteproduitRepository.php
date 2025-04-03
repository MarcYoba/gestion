<?php

namespace App\Repository;

use App\Entity\Quantiteproduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quantiteproduit>
 *
 * @method Quantiteproduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quantiteproduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quantiteproduit[]    findAll()
 * @method Quantiteproduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantiteproduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quantiteproduit::class);
    }

//    /**
//     * @return Quantiteproduit[] Returns an array of Quantiteproduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Quantiteproduit
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
