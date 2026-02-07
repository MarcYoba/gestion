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
            ->select('h.quantite')
            ->where('h.produitA = :produits')
            ->andWhere('h.createtAd = :date')
            ->andWhere('h.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('date', $date)
            ->setParameter('agences',$agence)
            ->orderBy('h.createtAd', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    
        return $result ? (int) $result['quantite'] : 0;
    }

    public function findByLastDate($date,$produit,$agence) : int
    {
        $datesuivant = (clone $date)->modify('+1 day');
        $result= $this->createQueryBuilder('h')
            ->select('h.quantite')
            ->where('h.produitA = :produits')
            ->andWhere('h.createtAd = :date')
            ->andWhere('h.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('date', $datesuivant)
            ->setParameter('agences',$agence)
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    
        return $result ? (int) $result['quantite'] : 0;
    }

    public function findForDate($date,$produit,$agence) : ?HistoriqueA
    {
        return $this->createQueryBuilder('h')
            ->where('h.produitA = :produits')
            ->andWhere('h.createtAd = :date')
            ->andWhere('h.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('date', $date)
            ->setParameter('agences',$agence)
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByProduitAgence($produit,$agence,$date) : ?HistoriqueA
    {
        return $this->createQueryBuilder('h')
            ->where('h.produitA = :produits')
            ->andWhere('h.agence = :agences')
            ->andWhere('h.createtAd =:date')
            ->setParameter('produits',$produit)
            ->setParameter('agences',$agence)
            ->setParameter('date',$date)
            ->orderBy('h.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByHistoriquePeriode($dateDebut, $dateFin, $agence) : array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.agence = :agences')
            ->andWhere('h.createtAd >= :dateDebut')
            ->andWhere('h.createtAd <= :dateFin')
            ->setParameter('agences',$agence)
            ->setParameter('dateDebut',$dateDebut)
            ->setParameter('dateFin',$dateFin)
            ->orderBy('h.produitA', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
