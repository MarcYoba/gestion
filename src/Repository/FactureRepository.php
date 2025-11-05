<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }

//    /**
//     * @return Facture[] Returns an array of Facture objects
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

//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBySommeProduit($date,$produit,$agence) : int
    {
        $result= $this->createQueryBuilder('f')
            ->select('COALESCE(SUM(f.quantite), 0)')
            ->where('f.produit = :produits')
            ->andWhere('YEAR(f.createdAt) = :date')
            ->andWhere('f.agence = :agences')
            ->setParameter('produits',$produit)
            ->setParameter('date', $date)
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
            ->where('f.createdAt  BETWEEN :startDate AND :endDate')
            ->andWhere('f.agence = :agences')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->orderBy('f.produit','ASC')
            ->groupBy('f.produit')
            ->getQuery()
            ->getResult();

    }
}
