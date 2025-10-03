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
    public function findByRefDate($ref, $date) : ?Passif
    {
        return $this->createQueryBuilder('p')
            ->where('p.REF = :refference')
            ->andWhere('YEAR(p.created) = :date')
            ->setParameter('refference',$ref)
            ->setParameter('date',$date)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByYear($anne) : array 
    {
        return $this->createQueryBuilder('p')
            ->andWhere('YEAR(p.created) = :date')
            ->setParameter('date',$anne)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySomme($anne,$categorie) : array 
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.montant)')
            ->where('p.cathegorie = :refere')
            ->andWhere('YEAR(p.created) = :date')
            ->setParameter('refere',$categorie)
            ->setParameter('date',$anne)
            ->getQuery()
            ->getResult()
        ;

    }
}
