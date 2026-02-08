<?php

namespace App\Repository;

use App\Entity\BondCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BondCommande>
 *
 * @method BondCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method BondCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method BondCommande[]    findAll()
 * @method BondCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BondCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BondCommande::class);
    }

//    /**
//     * @return BondCommande[] Returns an array of BondCommande objects
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

//    public function findOneBySomeField($value): ?BondCommande
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findBySommeBonCommande(): float
   {
       $query = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
           ->andWhere('b.statut > :val')
           ->setParameter('val', 0)
           ->getQuery()
       ;

        $result = $query->getSingleScalarResult();
        return $result !== null ? (float) $result : 0.0;
   }

   public function findByProduitACommander(): array
   {
       return $this->createQueryBuilder('b')
           ->andWhere('b.statut > :val')
           ->setParameter('val', 0)
           ->orderBy('b.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }
}
