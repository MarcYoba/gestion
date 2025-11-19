<?php

namespace App\Repository;

use App\Entity\Sociales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sociales>
 *
 * @method Sociales|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sociales|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sociales[]    findAll()
 * @method Sociales[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sociales::class);
    }

//    /**
//     * @return Sociales[] Returns an array of Sociales objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sociales
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
