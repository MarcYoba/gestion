<?php

namespace App\Repository;

use App\Entity\HistoriqueA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoriqueA>
 *
 * @method HistoriqueA|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoriqueA|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoriqueA[]    findAll()
 * @method HistoriqueA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoriqueARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriqueA::class);
    }

//    /**
//     * @return HistoriqueA[] Returns an array of HistoriqueA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HistoriqueA
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByDate($date,$produit,$agence) : int
    {
        $result= $this->createQueryBuilder('h')
            ->select('COALESCE(SUM(h.quantite), 0)')
            ->where('h.produitA = :produits')
            ->andWhere('h.createtAd = :date')
            ->andWhere('h.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('date', $date)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    
        return $result > 0 ? (int)$result : 0;
    }

    public function findByLastDate($date,$produit,$agence) : int
    {
        $datesuivant = (clone $date)->modify('+1 day');
        $result= $this->createQueryBuilder('h')
            ->select('COALESCE(SUM(h.quantite), 0)')
            ->where('h.produitA = :produits')
            ->andWhere('h.createtAd = :date')
            ->andWhere('h.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('date', $datesuivant)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    
        return $result > 0 ? (int)$result : 0;
    }
}
