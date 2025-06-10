<?php

namespace App\Repository;

use App\Entity\TempAgence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TempAgence>
 *
 * @method TempAgence|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempAgence|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempAgence[]    findAll()
 * @method TempAgence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempAgenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempAgence::class);
    }

//    /**
//     * @return TempAgence[] Returns an array of TempAgence objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TempAgence
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
