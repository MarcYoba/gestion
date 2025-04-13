<?php

namespace App\Repository;

use App\Entity\ProduitA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProduitA>
 *
 * @method ProduitA|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitA|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitA[]    findAll()
 * @method ProduitA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitA::class);
    }

//    /**
//     * @return ProduitA[] Returns an array of ProduitA objects
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

//    public function findOneBySomeField($value): ?ProduitA
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
