<?php

namespace App\Repository;

use App\Entity\InventaireA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InventaireA>
 *
 * @method InventaireA|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventaireA|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventaireA[]    findAll()
 * @method InventaireA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventaireARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventaireA::class);
    }

//    /**
//     * @return InventaireA[] Returns an array of InventaireA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InventaireA
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
