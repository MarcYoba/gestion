<?php

namespace App\Repository;

use App\Entity\Brouillon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Brouillon>
 *
 * @method Brouillon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brouillon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brouillon[]    findAll()
 * @method Brouillon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrouillonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brouillon::class);
    }

//    /**
//     * @return Brouillon[] Returns an array of Brouillon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Brouillon
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByGroupe(){
        return $this->createQueryBuilder('b')
        //    ->andWhere('b.exampleField = :val')
        //    ->setParameter('val', $value)
           ->orderBy('b.id', 'ASC')
           ->groupBy('b.facture')
           ->getQuery()
           ->getResult()
       ;
    }
}
