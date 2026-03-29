<?php

namespace App\Repository;

use App\Entity\InventaireA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InventaireA>
 *
 * @method InventaireA|null find($id, $lockMode = null, $lockVersion = null)
 * @method InventaireA|null findOneBy(array $criteria, array $orderBy = null)
 * @method InventaireA[]    findAll()
 * @method InventaireA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InventaireARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventaireA::class);
    }

//    /**
//     * @return InventaireA[] Returns an array of InventaireA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InventaireA
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByproduit($agence,$moi,$anne) : array 
    {
        return $this-> createQueryBuilder('i')
            ->where('i.agence =:agences')
            ->andWhere('MONTH(i.createtAt) =:moi')
            ->andWhere('YEAR(i.createtAt) =:anne')
            ->setParameter('agences',$agence)
            ->setParameter('moi',$moi)
            ->setParameter('anne',$anne)
            ->groupBy('i.produit')
            ->orderBy('i.produit','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByQuantiteProduit($agence,$moi,$anne) : array 
    {
        return $this-> createQueryBuilder('i')
            ->where('i.agence =:agences')
            ->andWhere('MONTH(i.createtAt) =:moi')
            ->andWhere('YEAR(i.createtAt) =:anne')
            ->setParameter('agences',$agence)
            ->setParameter('moi',$moi)
            ->setParameter('anne',$anne)
            ->orderBy('i.createtAt','ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySommeMois($agence,$moi,$anne,$produit) : int
    {
        $resultut = $this-> createQueryBuilder('i')
            ->select('SUM(i.ecart)')
            ->where('i.agence =:agences')
            ->andWhere('MONTH(i.createtAt) =:moi')
            ->andWhere('YEAR(i.createtAt) =:anne')
            ->andWhere('i.produit =:produit')
            ->setParameter('agences',$agence)
            ->setParameter('moi',$moi)
            ->setParameter('anne',$anne)
            ->setParameter('produit',$produit)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return empty($resultut) ? 0 : $resultut;
    }

    public function findBySommeDate($agence,$moi,$anne) : array 
    {
        return $this-> createQueryBuilder('i')
            ->select('SUM(i.ecart)')
            ->where('i.agence =:agences')
            ->andWhere('MONTH(i.createtAt) =:moi')
            ->andWhere('YEAR(i.createtAt) =:anne')
            ->setParameter('agences',$agence)
            ->setParameter('moi',$moi)
            ->setParameter('anne',$anne)
            ->groupBy('i.createtAt')
            ->orderBy('i.createtAt','ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
