<?php

namespace App\Repository;

use App\Entity\Passif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Passif>
 *
 * @method Passif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Passif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Passif[]    findAll()
 * @method Passif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PassifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Passif::class);
    }

//    /**
//     * @return Passif[] Returns an array of Passif objects
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

//    public function findOneBySomeField($value): ?Passif
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
