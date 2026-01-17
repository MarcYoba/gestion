<?php

namespace App\Repository;

use App\Entity\MagasinA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MagasinA>
 *
 * @method MagasinA|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagasinA|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagasinA[]    findAll()
 * @method MagasinA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagasinA::class);
    }

//    /**
//     * @return MagasinA[] Returns an array of MagasinA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MagasinA
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
