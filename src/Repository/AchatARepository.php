<?php

namespace App\Repository;

use App\Entity\AchatA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AchatA>
 *
 * @method AchatA|null find($id, $lockMode = null, $lockVersion = null)
 * @method AchatA|null findOneBy(array $criteria, array $orderBy = null)
 * @method AchatA[]    findAll()
 * @method AchatA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AchatA::class);
    }

//    /**
//     * @return AchatA[] Returns an array of AchatA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AchatA
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findByDate($date) : array
    {
        $startDate = (clone $date)->setTime(0,0,0);
        $endDate = (clone $date)->setTime(23,59,59);
        return $this->createQueryBuilder('a')
            ->where('a.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate',$startDate)
            ->setParameter('endDate',$endDate)
            ->getQuery()
            ->getResult()
        ;

    }

    public function findBySommeAchatProduit($date,$produit,$agence) : int
    {
        $result = $this->createQueryBuilder('a')
            ->select('COALESCE(SUM(a.quantite), 0)')
            ->where('YEAR(a.createdAt) = :date')
            ->andWhere('a.produit = :val')
            ->andWhere('a.agence = :agences')
            ->setParameter('date',$date)
            ->setParameter('val', $produit)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    
        return $result > 0 ? (int)$result : 0;
    }
}
