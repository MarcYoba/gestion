<?php

namespace App\Repository;

use App\Entity\CaisseA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CaisseA>
 *
 * @method CaisseA|null find($id, $lockMode = null, $lockVersion = null)
 * @method CaisseA|null findOneBy(array $criteria, array $orderBy = null)
 * @method CaisseA[]    findAll()
 * @method CaisseA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaisseARepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CaisseA::class);
    }

//    /**
//     * @return CaisseA[] Returns an array of CaisseA objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CaisseA
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findRapportCaisseToWeek($mois, $annee) : array 
    {
        $mois = (int)$mois;
        $annee = (int)$annee;
        $startDate = new \DateTime("$annee-$mois-01");
        $endDate = (clone $startDate)->modify('last day of this month');

        return $this->createQueryBuilder('c')
            ->select('COALESCE(SUM(c.montant),0) AS somme, CONCAT(\',\', c.motif) AS Motif, CONCAT(\',\', c.operation) AS element ') 
            ->where('c.createAt BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult()
        
        ;
    }

    public function findByCaisseSemaine($startDate, $endDate, $agence) : array 
    {
    
        return $this->createQueryBuilder('c') 
            ->where('c.createAt BETWEEN :startDate AND :endDate')
            ->andWhere('c.agence =:agences')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getResult()
        
        ;
    }
    
    public function findBySommeCaisse($date,$agence) : float 
    {
        $result = $this->createQueryBuilder('c')
            ->select('COALESCE(SUM(c.montant),0) AS Montant')
            ->Where('c.createAt = :val')
            ->andWhere('c.agence = :agences')
            ->setParameter('val', $date)
            ->setParameter('agences',$agence)
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $result > 0 ? (float)$result : 0;
    }
}
