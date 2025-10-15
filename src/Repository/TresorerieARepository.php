<?php

namespace App\Repository;

use App\Entity\TresorerieA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TresorerieA>
 *
 * @method TresorerieA|null find($id, $lockMode = null, $lockVersion = null)
 * @method TresorerieA|null findOneBy(array $criteria, array $orderBy = null)
 * @method TresorerieA[]    findAll()
 * @method TresorerieA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TresorerieARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TresorerieA::class);
    }

//    /**
//     * @return TresorerieA[] Returns an array of TresorerieA objects
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

//    public function findOneBySomeField($value): ?TresorerieA
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
