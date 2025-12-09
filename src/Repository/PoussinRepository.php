<?php

namespace App\Repository;

use App\Entity\Poussin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Poussin>
 *
 * @method Poussin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Poussin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Poussin[]    findAll()
 * @method Poussin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoussinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Poussin::class);
    }

//    /**
//     * @return Poussin[] Returns an array of Poussin objects
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

//    public function findOneBySomeField($value): ?Poussin
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByDay($date) : array 
    {
        return $this->createQueryBuilder('p')
            ->where('p.datecommande = :val')
            ->setParameter('val',$date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByMoi($moi,$anne) : array 
    {
        return $this->createQueryBuilder('p')
            ->select('CONCAT(\',\',p.prix) AS Prix,SUM(p.montant) AS Montant,SUM(p.mobilepay) AS Mobilepay, SUM(p.credit) AS Credit, SUM(p.cash) AS Cash,SUM(p.quantite) AS Quantite')
            ->where('MONTH(p.datecommande) = :moi')
            ->andWhere('YEAR(p.datecommande) = :anne')
            ->setParameter('moi',$moi)
            ->setParameter('anne',$anne)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCommandePoussin($agence) : array 
    {
        return $this->createQueryBuilder('p')
            ->where('p.status =:val')
            ->andWhere('p.agence =:agences')
            ->setParameter('val','EN COUR')
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCommandePoussinTrie($agence,$first_date,$end_date,$status) : array 
    {
        return $this->createQueryBuilder('p')
            ->where('p.status =:val')
            ->andWhere('p.datecommande BETWEEN :startDate AND :endDate')
            ->andWhere('p.agence =:agences')
            ->setParameter('val',$status)
            ->setParameter('agences',$agence)
            ->setParameter('startDate',$first_date)
            ->setParameter('endDate',$end_date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllCommandPoussin($agence,$first_date,$end_date) : array 
    {
        return $this->createQueryBuilder('p')
            ->Where('p.datecommande BETWEEN :startDate AND :endDate')
            ->andWhere('p.agence =:agences')
            ->setParameter('agences',$agence)
            ->setParameter('startDate',$first_date)
            ->setParameter('endDate',$end_date)
            ->getQuery()
            ->getResult()
        ;
    }
}
