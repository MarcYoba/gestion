<?php

namespace App\Repository;

use App\Entity\FactureA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactureA>
 *
 * @method FactureA|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureA|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureA[]    findAll()
 * @method FactureA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureA::class);
    }

//    /**
//     * @return FactureA[] Returns an array of FactureA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FactureA
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function FindByProduitPlusVendu() : array {

        return $this->createQueryBuilder('f')
            ->select('p.id as produitId, p.nom as produitNom, COUNT(f.id) as produitCount, p.quantite as produitQt')
            ->join('f.produit', 'p') // Jointure avec l'entité Produit
            ->where('MONTH(f.createAt) = :month')
            ->andWhere('YEAR(f.createAt) = :year')
            ->setParameter('month', (int)date('m'))
            ->setParameter('year', (int)date('Y'))
            ->groupBy('p.id, p.nom') // Grouper par les champs sélectionnés
            ->orderBy('produitCount', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }

    public function findBySommeProduit($date,$produit,$agence) : int
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);
        $result= $this->createQueryBuilder('f')
            ->select('COALESCE(SUM(f.quantite), 0)')
            ->where('f.produit = :produits')
            ->andWhere('f.createAt  BETWEEN :startDate AND :endDate')
            ->andWhere('f.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    
        return $result > 0 ? (int)$result : 0;
    }

    public function findByProduitVendu($date,$agence) : array 
    {
        // $date = new \DateTimeImmutable($date);
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);

       return $this->createQueryBuilder('f')
            ->where('f.createAt  BETWEEN :startDate AND :endDate')
            ->andWhere('f.agence = :agences')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->orderBy('f.produit','ASC')
            ->groupBy('f.produit')
            ->getQuery()
            ->getResult();

    }

    public function findByQuantiteProduitVendu($date, $produit, $agence): int
    {
        $startDate = (clone $date)->setTime(0, 0, 0);
        $endDate = (clone $date)->setTime(23, 59, 59);

        $result = $this->createQueryBuilder('f')
            ->select('COALESCE(SUM(f.quantite), 0)')
            ->where('f.produit = :produit')
            ->andWhere('f.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('f.agence = :agence')
            ->setParameter('produit', $produit)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agence', $agence)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }

    public function findByProduitVenduSemaine($first_date,$end_date,$agence) : array 
    {
        // $date = new \DateTimeImmutable($date);
        $startDate = (clone $first_date)->setTime(0, 0, 0);
        $endDate = (clone $end_date)->setTime(23, 59, 59);

       return $this->createQueryBuilder('f')
            ->where('f.createAt  BETWEEN :startDate AND :endDate')
            ->andWhere('f.agence = :agences')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->orderBy('f.produit','ASC')
            ->groupBy('f.produit')
            ->getQuery()
            ->getResult();

    }

    public function findByQuantiteProduitVenduAnne($date, $produit, $agence): int
    {

        $result = $this->createQueryBuilder('f')
            ->select('COALESCE(SUM(f.quantite), 0)')
            ->where('f.produit = :produit')
            ->andWhere('YEAR(f.createAt) = :startDate')
            ->andWhere('f.agence = :agence')
            ->setParameter('produit', $produit)
            ->setParameter('startDate', $date)
            ->setParameter('agence', $agence)
            ->getQuery()
            ->getSingleScalarResult();

        return (int) $result;
    }

    public function findByFactureProduit($first_date,$end_date,$agence,$produit) : array 
    {
        // $date = new \DateTimeImmutable($date);
        $startDate = (clone $first_date)->setTime(0, 0, 0);
        $endDate = (clone $end_date)->setTime(23, 59, 59);

       return $this->createQueryBuilder('f')
            ->where('f.createAt  BETWEEN :startDate AND :endDate')
            ->andWhere('f.agence = :agences')
            ->andWhere('f.produit =:produits')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->setParameter('produits',$produit)
            ->orderBy('f.produit','ASC')
            ->groupBy('f.produit')
            ->getQuery()
            ->getResult();

    }
}
