<?php

namespace App\Repository;

use App\Entity\EmpruntA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmpruntA>
 *
 * @method EmpruntA|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmpruntA|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmpruntA[]    findAll()
 * @method EmpruntA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmpruntA::class);
    }

//    /**
//     * @return EmpruntA[] Returns an array of EmpruntA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmpruntA
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
